//<script>
(function($) {
    var slideIndex = 1;
    var id = 0;
    var totalImages = 0;
    var sid = 0;
    var mouseOnContainer = false;
    var width = 1;

    $.fn.fbStories = function(options) {
        var settings = $.extend({
            barColor: "#F06",
            delay: 1,
            dataurl: './json.php',
            showAdd: true,
            addText: '&#43;',
            onShow: function(guid, url) {},
        }, options);
        var $element = this;
        if (!settings.dataurl) {
            console.log('There is no data URL');
            return false;
        }
        $('body').on('mouseover touchstart', '.fbstories-container .slideshow-container', function() {
            mouseOnContainer = true;
        });
        $('body').on('mouseleave touchend', '.fbstories-container .slideshow-container', function() {
            mouseOnContainer = false;
        });
        showProgress = function() {
            $(".fbstories-container .status-bar").hide();
            $(".fbstories-container .status-bar").progressbar({
                value: 0,
            });
            $('.fbstories-container .status-bar .ui-progressbar-value').css({
                background: settings.barColor
            });
            $(".fbstories-container .status-bar").show();

            id = setInterval(progress, (settings.delay / 100) * 1000);

            function progress() {
                if (width >= 100 && slideIndex >= totalImages) {
                    clearInterval(id);
                    clearInterval(sid);
                }
                if (mouseOnContainer == false) {
                    if (width >= 100 && slideIndex >= totalImages) {
                        clearInterval(id);
                    } else {
                        width++;
                        $(".status-bar").progressbar({
                            value: width,
                        });
                    }
                }
            }
        };
        //https://ourcodeworld.com/articles/read/278/how-to-split-an-array-into-chunks-of-the-same-size-easily-in-javascript
        function chunkArray(myArray, chunk_size) {
            var index = 0;
            var arrayLength = myArray.length;
            var tempArray = [];

            for (index = 0; index < arrayLength; index += chunk_size) {
                myChunk = myArray.slice(index, index + chunk_size);
                tempArray.push(myChunk);
            }
            return tempArray;
        }

        function initialize() {
            $element.prepend('<div class="fbstories-outside-container"> <div class="fbstories-items"></div></div>');
            $('body').prepend('<div class="fbstories-container"> <div class="fbstories-container-inner"> </div> </div>');

            if (settings.showAdd) {
                $('.fbstories-items').prepend('<div class="fbstories-item-add" style=""> <div class="image0th"> <a class="fbstories-add-story" href="javascript:void(0);">' + settings.addText + '</a></div></div>');
            }
        }

        function appendThumbs() {
            $.get(settings.dataurl, function(data) {
                //make the thumbnails
                if (data[0].files && data[0].files.length > 0) {
                    $thumbs = [];
                    $.each(data, function() {
                        $file = this['files'][0];
                        $border = '';
                        if ($file['viewed'] && $file['viewed'] == true) {
                            $border = ' story-viewed';
                        }
                        $thumbs.push({
                            'thumb': $file,
                            'owner': this['owner'],
                            'data': this,
                            'border': $border,
                        });
                    });
                    if ($thumbs.length > 0) {
                        $count = 1;
                        $thumbnails = '';
                        $($thumbs).each(function() {
                            $item = '';
                            $item += '<div class="fbstories-item' + this['border'] + '" data-items=\'' + JSON.stringify(this['data']) + '\'> <div class="user-image"> <img src="' + this['owner']['icon'] + '" width="32" height="32" /> </div> <div class="image0th"> <img src="' + this['thumb']['url'] + '" style="max-width:100%"> </div> <div class="user-name">' + this['owner']['fullname'] + '</div> </div>';
                            $thumbnails += $item;
                            $count++;
                        });
                        $('.fbstories-items').append($thumbnails);
                        touchSlide();
                    }
                }
            });
        }

        function touchSlide() {
            //https://kevinsimper.github.io/yearly-calendar-overview/
            //Thanks to KevinSimper for writing this slider tool for mouse touch
            const slider = document.querySelector(".fbstories-items");
            if (slider !== null) {
                let isDown = false;
                let startX;
                let scrollLeft;

                slider.addEventListener("mousedown", e => {
                    isDown = true;
                    slider.classList.add("active");
                    startX = e.pageX - slider.offsetLeft;
                    scrollLeft = slider.scrollLeft;
                });
                slider.addEventListener("mouseleave", () => {
                    isDown = false;
                    slider.classList.remove("active");
                });
                slider.addEventListener("mouseup", () => {
                    isDown = false;
                    slider.classList.remove("active");
                });
                slider.addEventListener("mousemove", e => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - slider.offsetLeft;
                    const walk = x - startX;
                    slider.scrollLeft = scrollLeft - walk;
                });
            }
        }

        function plusSlides(n) {
            clearInterval(id);
            width = 0;
            showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("fbstories-slides");
            if (n > slides.length) {
                //in case viewing last slide then keep it on that slide.
                slideIndex = slides.length;
            }
            if (n < 1) {
                slideIndex = 1
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            $('.slideshow-container .total-images .current').text(slideIndex);
            slides[slideIndex - 1].style.display = "block";

            settings.onShow(slides[slideIndex - 1].getAttribute('data-guid'), slides[slideIndex - 1].getAttribute('data-url'), slides[slideIndex - 1].getAttribute('data-owner'), slides[slideIndex - 1]);
            //set time
            $time = slides[slideIndex - 1].getAttribute('data-time');
            $('.fbstories-container .user-name-time .time').html($time);
            $('.fbstories-container .fbstorie-item-delete').attr('data-guid', slides[slideIndex - 1].getAttribute('data-guid'));
            $('.fbstories-container .fbstorie-item-delete').attr('data-owner', slides[slideIndex - 1].getAttribute('data-owner'));

            if (slideIndex <= totalImages) {
                showProgress();
            }
        }
        initialize();
        appendThumbs();

        $('body').on('click', '.slideshow-container .close', function() {
            width = 0;
            slideIndex = 1;
            mouseOnContainer = false;

            clearInterval(id);
            clearInterval(sid);

            $('.fbstories-container').hide();
            $('.fbstories-container-inner').html("");
        });
        $('body').on('click', '.slideshow-container .slide-button', function() {
            clearInterval(sid);
            var $index = parseInt($(this).attr('data-index'));
            plusSlides($index);
            sid = setInterval(function() {
                if (mouseOnContainer == false && width >= 100) {
                    plusSlides(1);
                    if (slideIndex == totalImages) {
                        clearInterval(sid);
                    }
                }
            }, 100);
        });

        $('body').on('click', '.fbstories-item', function() {
            slideIndex = 1;
            width = 0;
            id = 0;
            sid = 0;

            $json = JSON.parse($(this).attr('data-items'));
            if ($json['owner']['fullname']) {
                $container = '<div class="slideshow-container"> <div class="status-bar" style="display:none;"></div> <div class="close">&#10799;</div><div class="fbstorie-item-delete"><i class="fa fa-trash-alt"></i></div> <div class="users-information"> <div class="users-information-inner"> <div class="user-image"> <img src="' + $json['owner']['icon'] + '" width="32" height="32" /> </div> <div class="user-name-time"> <span class="name">' + $json['owner']['fullname'] + '</span> <span class="time"></span> </div> <div class="total-images"> <span class="current">1</span> <span>/</span> <span class="total">3</span> </div> </div> </div>';

                totalImages = $json['files'].length;

                $.each($json['files'], function() {
                    $container += '<div class="fbstories-slides fadefbstories" data-owner="' + $json['owner']['guid'] + '" data-time="' + this['time'] + '" data-url="' + this['url'] + '" data-guid="' + this['guid'] + '"> <img src="' + this['url'] + '" style="max-width:100%"> <div class="text">' + this['title'] + '</div> </div>';
                });

                $container += '<a class="prev slide-button"  data-index="-1">&#10094;</a> <a class="next slide-button" data-index="1">&#10095;</a> </div>';
                $('.fbstories-container-inner').html($container);
                $('.fbstories-container-inner .total-images .total').html($json['files'].length);

                $('.fbstories-container').show();
                showSlides(slideIndex);
            }
        });
        sid = setInterval(function() {
            if (mouseOnContainer == false && width >= 100) {
                if (slideIndex == totalImages) {
                    clearInterval(sid);
                } else {
                    plusSlides(1);
                }
            }
        }, 100);
        return this;
    };
}(jQuery));