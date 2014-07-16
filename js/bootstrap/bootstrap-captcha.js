(function ($) {
    "use strict";
    $.fn.bootstrapCaptcha = function (userOptions) {
        var that;
        this.attr('data-valid', 'false');
        this.attr('data-mouseUsed', 'false');
        this.iconSize = '3x';
        this.resetInvalidDrop = true;
        this.clearWhenCorrect = true;
        this.textPrompt = true;
        this.displayTargetArrows = true;
        this.resetTitle = 'Fallaste, intentalo de nuevo';
        that = $.extend(true, this, userOptions);
        if (that.onDrop && typeof that.onDrop === 'function') {
            that.callback = true;
        } else {
            that.callback = false;
        }
        that.guessedWrong = false;
        that.icons = [
                'envelope',
                'anchor',
                'pencil',
                'bullhorn',
                'fire-extinguisher',
                'camera',
                'wrench',
                'cut',
                'magic',
                'heart',
                'cogs',
                'trophy',
                'fire',
                'bell',
                'money',
                'truck',
                'coffee',
                'lightbulb-o',
                'paperclip',
                'lock',
                'credit-card',
                'headphones',
                'microphone',
                'rocket',
                'fighter-jet',
                'search',
                'beer',
                'eye',
                'magnet',
                'ambulance',
                'home',
                'glass',
                'video-camera',
                'gift',
                'book',
                'road',
                'star',
                'music',
                'user',
                'shield',
                'puzzle-piece',
                'bolt',
                'briefcase',
                'globe',
                'leaf',
                'circle-o',
                'calendar',
                'frown-o',
                'question-circle',
                'print',
                'smile-o',
                'key',
                'keyboard-o'
        ];
        that.iconNames = {
            envelope: 'sobre',
            anchor: 'ancla',
            pencil: 'lapiz',
            bullhorn: 'parlante',
            'fire-extinguisher': 'extintor',
            camera: 'camara',
            wrench: 'llave inglesa',
            cut: 'tijeras',
            magic: 'varita mágica',
            heart: 'corazón',
            cogs: 'engranajes',
            trophy: 'trofeo',
            fire: 'fuego',
            bell: 'campana',
            money: 'dinero',
            truck: 'camión',
            coffee: 'taza de café',
            'lightbulb-o': 'bombillo',
            paperclip: 'clip',
            lock: 'candado',
            'credit-card': 'tarjeta de crédito',
            headphones: 'audifonos',
            microphone: 'microfono',
            'smile-o': 'cara feliz',
            rocket: 'cohete',
            'fighter-jet': 'jet',
            search: 'lupa',
            beer: 'taza',
            'eye': 'ojo',
            magnet: 'iman',
            ambulance: 'ambulancia',
            home: 'casa',
            glass: 'copa',
            'video-camera': 'camara de video',
            gift: 'regalo',
            book: 'libro',
            'keyboard-o': 'teclado',
            road: 'carretera',
            star: 'estrella',
            music: 'nota musical',
            user: 'persona',
            shield: 'escudo',
            'puzzle-piece': 'pieza de rompecabeza',
            bolt: 'rayo',
            'question-circle': 'signo de interrogación',
            briefcase: 'maleta',
            globe: 'globo',
            leaf: 'hoja',
            'circle-o': 'circulo',
            calendar: 'calendario',
            'frown-o': 'cara triste',
            key: 'llave',
            print: 'impresora'
        };
        that.used = [];
        that.storedIcons = [];
        that.mouseUsed = false;
        that.bsValid = '';
        that.str = '';
        that.validate = function ($icon) {
            var klass = 'fa-' + that.bsValid,
                x = {
                    valid: false,
                    mouseUsed: false
                };
            that.attr('data-valid', 'false');
            that.attr('data-mouseUsed', 'false');
            if (!that.mouseUsed) {
                if (that.callback === true) {
                    that.onDrop(x);
                }
                return;
            }
            x.mouseUsed = true;
            that.attr('data-mouseUsed', 'true');
            if ($icon.hasClass(klass)) {
                that.attr('data-valid', 'true');
                x.valid = true;
                $('#bsBoop').append($('<i/>', {
                    'class': 'hide fa fa-check fa-' + that.iconSize + ' fa-' + that.bsValid,
                    id: 'bsValidIcon'
                }));
                $icon.hide();

                /*$('.fa-bullseye').fadeOut(function () {
                    $('#bsCaptchaTarget').removeClass('alert-danger').addClass('alert-success');
                    $('#bsValidIcon').fadeIn();
                });*/
                /*if (that.clearWhenCorrect === true) {
                    $('#bootstrapCaptchaDiv').slideUp();
                }*/
                if (that.callback === true) {
                    that.onDrop(x);
                }
                return;
            }
            if (that.resetInvalidDrop === true) {
                that.guessedWrong = true;
                that.makeLayout();
                return;
            }
            $('#bsCaptchaError').empty().append('Inténtalo de nuevo');
            if (that.callback === true) {
                that.onDrop(x);
            }
        };
        that.getRandomInt = function (min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        };
        that.addIcon = function () {
            var randomnumber = that.getRandomInt(0, (that.icons.length - 1));
            if ($.inArray(randomnumber, that.used) === -1) {
                that.used.push(randomnumber);
                $('<i/>', {
                    'class': 'bsDraggable fa fa-' + that.iconSize + ' fa-' + that.icons[randomnumber]
                }).appendTo('#bsCaptchaOut');
                $('#bsCaptchaOut').append('&nbsp;&nbsp;');
                that.storedIcons.push(that.icons[randomnumber]);
            }
            if (that.used.length < 6) {
                that.addIcon();
                return;
            }
            randomnumber = that.getRandomInt(0, (that.storedIcons.length - 1));
            if (that.textPrompt === true) {
                $('#bsWhat').empty().append('<strong>' + that.iconNames[that.storedIcons[randomnumber]] + '</strong>');
            } else {
                $('#bsWhat').empty().append($('<i/>', {
                    'class': 'fa fa-' + that.iconSize + ' fa-' + that.storedIcons[randomnumber]
                }));
                $('#bsWhat').append(' icon ');
            }
            $('#bsCaptchaTarget').append($('<li/>', {
                id: 'bsBoop'
            }));

            that.bsValid = that.storedIcons[randomnumber];
            $('.bsDraggable').draggable({
                revert: true,
                helper: "clone"
            }).on("mousedown", function () {
                that.mouseUsed = true;
            });
            $('#bsCaptchaTarget').droppable({
                accept: ".bsDraggable",
                activeClass: "ui-state-highlight",
                drop: function (event, ui) {
                    that.validate(ui.draggable);
                }
            });
            $('#bootstrapCaptchaDiv').slideDown();

        };
        //  credit: http://sedition.com/perl/javascript-fy.html
        that.fisherYates = function () {
            var i = that.icons.length,
                j, temp;
            if (i === 0) {
                return false;
            }
            while (--i) {
                j = Math.floor(Math.random() * (i + 1));
                temp = that.icons[i];
                that.icons[i] = that.icons[j];
                that.icons[j] = temp;
            }
            that.addIcon();
        };
        that.makeLayout = function () {
            that.used = [];
            that.storedIcons = [];
            that.mouseUsed = false;
            that.bsValid = '';
            that.str = '';
            that.empty();
            $('<div/>', {
                id: "bootstrapCaptchaDiv"
            }).appendTo(that);
            $('<div/>', {
                'class': 'row bsCaptchaDiv bsCaptchaRemove'
            }).appendTo('#bootstrapCaptchaDiv');
            $('<ul/>', {
                'class': 'text-center col-md-12',
                id: 'bsInstructions'
            }).appendTo('.bsCaptchaDiv:last').append('<li>Arrastra la imagen que representa un(a) <span id="bsWhat" class="label label-primary"></span> hacia abajo:</li>');
            if(that.guessedWrong === true){
                $('#bsInstructions').slideUp().prepend('<li class="alert alert-danger">' + that.resetTitle + '</li>').slideDown();
            }
            $('<div/>', {
                'class': "row bsCaptchaDiv bsCaptchaRemove"
            }).appendTo('#bootstrapCaptchaDiv');
            $('<ul/>', {
                'class': "col-md-12 text-center"
            }).appendTo('.bsCaptchaDiv:last').append('<li id="bsCaptchaOut"></li>');
            $('<div/>', {
                'class': 'row bsCaptchaRemove'
            }).appendTo('#bootstrapCaptchaDiv').append($('<p/>', {
                'class': 'col-md-12',
                html: '<hr>'
            }));
            $('<div/>', {
                'class': 'row bsCaptchaDiv'
            }).appendTo('#bootstrapCaptchaDiv');
            $('<ul/>', {
                'class': "col-md-12 text-center"
            }).appendTo('.bsCaptchaDiv:last').append('<li id="bsTargetSpanLI">&nbsp</li>');
            that.str = '<span id="bsTargetSpan">';
            if (that.displayTargetArrows) {
                that.str += '<i class="fa fa-arrow-down fa-' + that.iconSize + '"></i>';
                that.str += '&nbsp;<strong><span id="bsCaptchaError">Arrastra hacia aca</span></strong>&nbsp;';
                that.str += '<i class="fa fa-arrow-down fa-' + that.iconSize + '"></i>';
            } else {
                that.str += '<strong><span id="bsCaptchaError">to that target</span></strong>';
            }
            that.str += '</span>';
            $('#bsTargetSpanLI').append(that.str);
            $('<div/>', {
                'class': 'row bsCaptchaDiv'
            }).appendTo('#bootstrapCaptchaDiv');
            $('<ul/>', {
                'class': "col-md-4 col-md-offset-4 text-center well well-small alert alert-danger",
                id: 'bsCaptchaTarget'
            }).appendTo('.bsCaptchaDiv:last').append('<li><i class="fa fa-bullseye fa-' + that.iconSize + '"></i></li>');
            that.fisherYates();
        };
        if ($('body').hasClass('bootstrapCaptcha')) {
            return this;
        }
        $('body').addClass('bootstrapCaptcha');
        this.makeLayout();
        return this;
    };
}(jQuery));
