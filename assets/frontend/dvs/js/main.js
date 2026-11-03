document.addEventListener('DOMContentLoaded', function() {
    // Initialize Owl Carousel
    $('#homeSlider').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    // Initialize Magnific Popup
    $('.gallery-popup').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        },
        image: {
            titleSrc: 'title'
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        }
    });

    // Gallery Filter Functionality (vanilla)
    const galleryFilterBtns = document.querySelectorAll('.gallery-filter .filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    if (galleryFilterBtns && galleryItems) {
        galleryFilterBtns.forEach(button => {
            button.addEventListener('click', function() {
                // remove active from all
                galleryFilterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                galleryItems.forEach(item => {
                    const cat = item.getAttribute('data-category');
                    if (filter === 'all' || cat === filter) {
                        item.style.display = '';
                        item.style.opacity = '0';
                        setTimeout(() => { item.style.opacity = '1'; }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }

    // News carousel (Slick) + Filter Functionality
    // Initialize Slick on the news container
    if (typeof $ !== 'undefined' && typeof $.fn.slick === 'function') {
        $('.news-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            prevArrow: '<button type="button" class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                { breakpoint: 992, settings: { slidesToShow: 2 } },
                { breakpoint: 768, settings: { slidesToShow: 1 } }
            ]
        });

        // Use category-filter buttons (only those inside .category-filter)
        const newsFilterBtns = document.querySelectorAll('.category-filter .filter-btn');

        newsFilterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Reset button styles
                newsFilterBtns.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                    b.style.backgroundColor = '';
                    b.style.color = '';
                    b.style.borderColor = '';
                });

                // Highlight clicked button
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
                this.style.backgroundColor = 'var(--primary-color)';
                this.style.color = 'white';
                this.style.border = 'none';

                const selectedCategory = this.getAttribute('data-category');

                // Unfilter first
                $('.news-slider').slick('slickUnfilter');

                if (selectedCategory !== 'all') {
                    // Filter slides by data-category attribute on the slide element
                    $('.news-slider').slick('slickFilter', function() {
                        return $(this).data('category') === selectedCategory;
                    });
                }
            });
        });

        // Trigger default All
        const defaultBtn = document.querySelector('.category-filter [data-category="all"]');
        if (defaultBtn) defaultBtn.click();
    } else {
        // Fallback: if Slick not available, leave the static layout and just set All active
        const allButton = document.querySelector('[data-category="all"]');
        if (allButton) allButton.click();
    }
});

// Global getSectionByClass for frontend admission forms
function getSectionByClass(class_id) {
    var $sec = $('#online_section_id, #section_id, select[name="section"], select[name="section_id"]');
    if (!class_id) {
        $sec.html('<option value="">' + (typeof isEnglishLang !== 'undefined' && isEnglishLang ? 'Select Class First' : 'প্রথমে শ্রেণি নির্বাচন করুন') + '</option>');
        if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
            $sec.trigger('change.select2');
        }
        return;
    }

    $sec.html('<option value="">' + (typeof isEnglishLang !== 'undefined' && isEnglishLang ? 'Loading Sections...' : 'শাখা লোড হচ্ছে...') + '</option>');

    var postData = { class_id: class_id };
    if (typeof csrfData !== 'undefined' && csrfData) {
        $.extend(postData, csrfData);
    }

    var baseUrl = typeof base_url !== 'undefined' ? base_url : '/';
    $.ajax({
        url: baseUrl + 'home/getSectionByClass',
        type: 'POST',
        data: postData,
        dataType: 'html',
        success: function(response) {
            $sec.html(response);
            if ($.fn.select2 && $sec.hasClass('select2-hidden-accessible')) {
                $sec.trigger('change.select2');
            }
        },
        error: function() {
            $sec.html('<option value="">' + (typeof isEnglishLang !== 'undefined' && isEnglishLang ? 'No Sections Available' : 'শাখা পাওয়া যায়নি') + '</option>');
        }
    });
}
window.getSectionByClass = getSectionByClass;
