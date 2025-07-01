(function () {
    'use strict';

    var __assign = (undefined && undefined.__assign) || function () {
        __assign = Object.assign || function(t) {
            for (var s, i = 1, n = arguments.length; i < n; i++) {
                s = arguments[i];
                for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                    t[p] = s[p];
            }
            return t;
        };
        return __assign.apply(this, arguments);
    };
    var defaults = {
        lines: 12,
        length: 7,
        width: 5,
        radius: 10,
        scale: 1.0,
        corners: 1,
        color: '#000',
        fadeColor: 'transparent',
        animation: 'spinner-line-fade-default',
        rotate: 0,
        direction: 1,
        speed: 1,
        zIndex: 2e9,
        className: 'spinner',
        top: '50%',
        left: '50%',
        shadow: '0 0 1px transparent',
        position: 'absolute',
    };
    var Spinner = /** @class */ (function () {
        function Spinner(opts) {
            if (opts === void 0) { opts = {}; }
            this.opts = __assign(__assign({}, defaults), opts);
        }
        /**
         * Adds the spinner to the given target element. If this instance is already
         * spinning, it is automatically removed from its previous target by calling
         * stop() internally.
         */
        Spinner.prototype.spin = function (target) {
            this.stop();
            this.el = document.createElement('div');
            this.el.className = this.opts.className;
            this.el.setAttribute('role', 'progressbar');
            css(this.el, {
                position: this.opts.position,
                width: 0,
                zIndex: this.opts.zIndex,
                left: this.opts.left,
                top: this.opts.top,
                transform: "scale(" + this.opts.scale + ")",
            });
            if (target) {
                target.insertBefore(this.el, target.firstChild || null);
            }
            drawLines(this.el, this.opts);
            return this;
        };
        /**
         * Stops and removes the Spinner.
         * Stopped spinners may be reused by calling spin() again.
         */
        Spinner.prototype.stop = function () {
            if (this.el) {
                if (typeof requestAnimationFrame !== 'undefined') {
                    cancelAnimationFrame(this.animateId);
                }
                else {
                    clearTimeout(this.animateId);
                }
                if (this.el.parentNode) {
                    this.el.parentNode.removeChild(this.el);
                }
                this.el = undefined;
            }
            return this;
        };
        return Spinner;
    }());
    /**
     * Sets multiple style properties at once.
     */
    function css(el, props) {
        for (var prop in props) {
            el.style[prop] = props[prop];
        }
        return el;
    }
    /**
     * Returns the line color from the given string or array.
     */
    function getColor(color, idx) {
        return typeof color == 'string' ? color : color[idx % color.length];
    }
    /**
     * Internal method that draws the individual lines.
     */
    function drawLines(el, opts) {
        var borderRadius = (Math.round(opts.corners * opts.width * 500) / 1000) + 'px';
        var shadow = 'none';
        if (opts.shadow === true) {
            shadow = '0 2px 4px #000'; // default shadow
        }
        else if (typeof opts.shadow === 'string') {
            shadow = opts.shadow;
        }
        var shadows = parseBoxShadow(shadow);
        for (var i = 0; i < opts.lines; i++) {
            var degrees = ~~(360 / opts.lines * i + opts.rotate);
            var backgroundLine = css(document.createElement('div'), {
                position: 'absolute',
                top: -opts.width / 2 + "px",
                width: (opts.length + opts.width) + 'px',
                height: opts.width + 'px',
                background: getColor(opts.fadeColor, i),
                borderRadius: borderRadius,
                transformOrigin: 'left',
                transform: "rotate(" + degrees + "deg) translateX(" + opts.radius + "px)",
            });
            var delay = i * opts.direction / opts.lines / opts.speed;
            delay -= 1 / opts.speed; // so initial animation state will include trail
            var line = css(document.createElement('div'), {
                width: '100%',
                height: '100%',
                background: getColor(opts.color, i),
                borderRadius: borderRadius,
                boxShadow: normalizeShadow(shadows, degrees),
                animation: 1 / opts.speed + "s linear " + delay + "s infinite " + opts.animation,
            });
            backgroundLine.appendChild(line);
            el.appendChild(backgroundLine);
        }
    }
    function parseBoxShadow(boxShadow) {
        var regex = /^\s*([a-zA-Z]+\s+)?(-?\d+(\.\d+)?)([a-zA-Z]*)\s+(-?\d+(\.\d+)?)([a-zA-Z]*)(.*)$/;
        var shadows = [];
        for (var _i = 0, _a = boxShadow.split(','); _i < _a.length; _i++) {
            var shadow = _a[_i];
            var matches = shadow.match(regex);
            if (matches === null) {
                continue; // invalid syntax
            }
            var x = +matches[2];
            var y = +matches[5];
            var xUnits = matches[4];
            var yUnits = matches[7];
            if (x === 0 && !xUnits) {
                xUnits = yUnits;
            }
            if (y === 0 && !yUnits) {
                yUnits = xUnits;
            }
            if (xUnits !== yUnits) {
                continue; // units must match to use as coordinates
            }
            shadows.push({
                prefix: matches[1] || '',
                x: x,
                y: y,
                xUnits: xUnits,
                yUnits: yUnits,
                end: matches[8],
            });
        }
        return shadows;
    }
    /**
     * Modify box-shadow x/y offsets to counteract rotation
     */
    function normalizeShadow(shadows, degrees) {
        var normalized = [];
        for (var _i = 0, shadows_1 = shadows; _i < shadows_1.length; _i++) {
            var shadow = shadows_1[_i];
            var xy = convertOffset(shadow.x, shadow.y, degrees);
            normalized.push(shadow.prefix + xy[0] + shadow.xUnits + ' ' + xy[1] + shadow.yUnits + shadow.end);
        }
        return normalized.join(', ');
    }
    function convertOffset(x, y, degrees) {
        var radians = degrees * Math.PI / 180;
        var sin = Math.sin(radians);
        var cos = Math.cos(radians);
        return [
            Math.round((x * cos + y * sin) * 1000) / 1000,
            Math.round((-x * sin + y * cos) * 1000) / 1000,
        ];
    }

    /*!
     * Ladda
     * http://lab.hakim.se/ladda
     * MIT licensed
     *
     * Copyright (C) 2018 Hakim El Hattab, http://hakim.se
     */

    /**
     * Creates a new instance of Ladda which wraps the
     * target button element.
     *
     * @return An API object that can be used to control
     * the loading animation state.
     */
    function create(button) {
        if (typeof button === 'undefined') {
            console.warn("Ladda button target must be defined.");
            return;
        }

        // The button must have the class "ladda-button"
        if (!button.classList.contains('ladda-button')) {
            button.classList.add('ladda-button');
        }

        // Style is required, default to "expand-right"
        if (!button.hasAttribute('data-style')) {
            button.setAttribute('data-style', 'expand-right');
        }

        // The text contents must be wrapped in a ladda-label
        // element, create one if it doesn't already exist
        if (!button.querySelector('.ladda-label')) {
            var laddaLabel = document.createElement('span');
            laddaLabel.className = 'ladda-label';
            wrapContent(button, laddaLabel);
        }

        // The spinner component
        var spinnerWrapper = button.querySelector('.ladda-spinner');

        // Wrapper element for the spinner
        if (!spinnerWrapper) {
            spinnerWrapper = document.createElement('span');
            spinnerWrapper.className = 'ladda-spinner';
        }

        button.appendChild(spinnerWrapper);

        // Timer used to delay starting/stopping
        var timer;
        var spinner;

        var instance = {
            /**
             * Enter the loading state.
             */
            start: function() {
                // Create the spinner if it doesn't already exist
                if (!spinner) {
                    spinner = createSpinner(button);
                }

                button.disabled = true;
                button.setAttribute('data-loading', '');

                clearTimeout(timer);
                spinner.spin(spinnerWrapper);

                this.setProgress(0);

                return this; // chain
            },

            /**
             * Enter the loading state, after a delay.
             */
            startAfter: function(delay) {
                clearTimeout(timer);
                timer = setTimeout(function() { instance.start(); }, delay);

                return this; // chain
            },

            /**
             * Exit the loading state.
             */
            stop: function() {
                if (instance.isLoading()) {
                    button.disabled = false;
                    button.removeAttribute('data-loading');   
                }

                // Kill the animation after a delay to make sure it
                // runs for the duration of the button transition
                clearTimeout(timer);

                if (spinner) {
                    timer = setTimeout(function() { spinner.stop(); }, 1000);
                }

                return this; // chain
            },

            /**
             * Toggle the loading state on/off.
             */
            toggle: function() {
                return this.isLoading() ? this.stop() : this.start();
            },

            /**
             * Sets the width of the visual progress bar inside of
             * this Ladda button
             *
             * @param {number} progress in the range of 0-1
             */
            setProgress: function(progress) {
                // Cap it
                progress = Math.max(Math.min(progress, 1), 0);

                var progressElement = button.querySelector('.ladda-progress');

                // Remove the progress bar if we're at 0 progress
                if (progress === 0 && progressElement && progressElement.parentNode) {
                    progressElement.parentNode.removeChild(progressElement);
                } else {
                    if (!progressElement) {
                        progressElement = document.createElement('div');
                        progressElement.className = 'ladda-progress';
                        button.appendChild(progressElement);
                    }

                    progressElement.style.width = ((progress || 0) * button.offsetWidth) + 'px';
                }
            },

            isLoading: function() {
                return button.hasAttribute('data-loading');
            },

            remove: function() {
                clearTimeout(timer);
                button.disabled = false;
                button.removeAttribute('data-loading');

                if (spinner) {
                    spinner.stop();
                    spinner = null;
                }
            }
        };

        return instance;
    }

    /**
     * Binds the target buttons to automatically enter the
     * loading state when clicked.
     *
     * @param target Either an HTML element or a CSS selector.
     * @param options
     *          - timeout Number of milliseconds to wait before
     *            automatically cancelling the animation.
     *          - callback A function to be called with the Ladda
     *            instance when a target button is clicked.
     */
    function bind(target, options) {
        var targets;

        if (typeof target === 'string') {
            targets = document.querySelectorAll(target);
        } else if (typeof target === 'object') {
            targets = [target];
        } else {
            throw new Error('target must be string or object');
        }

        options = options || {};

        for (var i = 0; i < targets.length; i++) {
            bindElement(targets[i], options);
        }
    }

    /**
    * Get the first ancestor node from an element, having a
    * certain type.
    *
    * @param elem An HTML element
    * @param type an HTML tag type (uppercased)
    *
    * @return An HTML element
    */
    function getAncestorOfTagType(elem, type) {
        while (elem.parentNode && elem.tagName !== type) {
            elem = elem.parentNode;
        }

        return (type === elem.tagName) ? elem : undefined;
    }

    function createSpinner(button) {
        var height = button.offsetHeight,
            spinnerColor,
            spinnerLines;

        if (height === 0) {
            // We may have an element that is not visible so
            // we attempt to get the height in a different way
            height = parseFloat(window.getComputedStyle(button).height);
        }

        // If the button is tall we can afford some padding
        if (height > 32) {
            height *= 0.8;
        }

        // Prefer an explicit height if one is defined
        if (button.hasAttribute('data-spinner-size')) {
            height = parseInt(button.getAttribute('data-spinner-size'), 10);
        }

        // Allow buttons to specify the color of the spinner element
        if (button.hasAttribute('data-spinner-color')) {
            spinnerColor = button.getAttribute('data-spinner-color');
        }

        // Allow buttons to specify the number of lines of the spinner
        if (button.hasAttribute('data-spinner-lines')) {
            spinnerLines = parseInt(button.getAttribute('data-spinner-lines'), 10);
        }

        var radius = height * 0.2,
            length = radius * 0.6,
            width = radius < 7 ? 2 : 3;

        return new Spinner({
            color: spinnerColor || '#fff',
            lines: spinnerLines || 12,
            radius: radius,
            length: length,
            width: width,
            animation: 'ladda-spinner-line-fade',
            zIndex: 'auto',
            top: 'auto',
            left: 'auto',
            className: ''
        });
    }

    function wrapContent(node, wrapper) {
        var r = document.createRange();
        r.selectNodeContents(node);
        r.surroundContents(wrapper);
        node.appendChild(wrapper);
    }

    function bindElement(element, options) {
        if (typeof element.addEventListener !== 'function') {
            return;
        }

        var instance = create(element);
        var timeout = -1;

        element.addEventListener('click', function() {
            // If the button belongs to a form, make sure all the
            // fields in that form are filled out
            var valid = true;
            var form = getAncestorOfTagType(element, 'FORM');

            if (typeof form !== 'undefined' && !form.hasAttribute('novalidate')) {
                // Modern form validation
                if (typeof form.checkValidity === 'function') {
                    valid = form.checkValidity();
                }
            }

            if (valid) {
                // This is asynchronous to avoid an issue where disabling
                // the button prevents forms from submitting
                instance.startAfter(1);

                // Set a loading timeout if one is specified
                if (typeof options.timeout === 'number') {
                    clearTimeout(timeout);
                    timeout = setTimeout(instance.stop, options.timeout);
                }

                // Invoke callbacks
                if (typeof options.callback === 'function') {
                    options.callback.apply(null, [instance]);
                }
            }

        }, false);
    }

    // Bind normal buttons
    bind('.button-demo button', { timeout: 2000 });

    // Bind progress buttons and simulate loading progress
    bind('.progress-demo button', {
        callback: function (instance) {
            var progress = 0;
            var interval = setInterval(function () {
                progress = Math.min(progress + Math.random() * 0.1, 1);
                instance.setProgress(progress);

                if (progress === 1) {
                    instance.stop();
                    clearInterval(interval);
                }
            }, 200);
        }
    });

    // You can control loading explicitly using the JavaScript API
    // as outlined below:

    // var l = Ladda.create( document.querySelector( 'button' ) );
    // l.start();
    // l.stop();
    // l.toggle();
    // l.isLoading();
    // l.setProgress( 0-1 );

})();
