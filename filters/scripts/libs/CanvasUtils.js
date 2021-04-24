// references:
//
// https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/filter
//
//
//
(function(canvasUtils) {

    window.canvasUtils = window.canvasUtils || canvasUtils(window, document);

})(function(window, document) {

    canvasUtils = {};

    canvasUtils.VERSION = 1.0;

    //Util class constructor
    canvasUtils.Util = function(image, canvas) {

            this.image = image;
            this.canvas = canvas || document.createElement("canvas");
            this.canvasCxt = this.canvas.getContext('2d');
            //canvas dimensions
            this.canvas.width = this.image.width;
            this.canvas.height = this.image.height;
            //Making Canvas as a perfect square
            var adjustment = Math.abs(this.canvas.width - this.canvas.height);
            if (this.canvas.width > this.canvas.height) {
                this.canvas.height += adjustment;
            } else {
                this.canvas.width += adjustment;
            }
            //rotation value should lie between 0 and 360
            this.rotation = 0; //need to delete this
            this.filters = [];

        }

    //Util prototype
    canvasUtils.Util.prototype = (function() {

        function _draw() {
            //clean the canvas
            this.canvasCxt.clearRect(0, 0, this.canvas.width, this.canvas.height);
            //settings
            this.canvasCxt.drawImage(this.image, 0, 0, this.image.width, this.image.height);
        }

        return {
            // Converts image to canvas; returns new canvas element
            paint: function() {
                _draw.apply(this);
                return this.canvas;
            },
            //applies specified filter
            applyFilter: function(filter, resetFilters) {
                resetFilters = resetFilters || true;
                if (resetFilters) {
                    this.filters = [];
                }
                if (filter) this.filters.push(filter);
                if (this.filters.length > 0) {
                    this.canvasCxt.filter = this.filters.toString().replace(",", " ");
                } else {
                    this.canvasCxt.filter = "none";
                }

                _draw.apply(this);
            },
            //rotates the image and paints
            rotate: function(direction) {
            	var rotation = 0;
                switch (direction) {
                    case "left":
                        rotation -= 90;
                        this.canvasCxt.translate(0, this.canvas.height);
                        break;
                    case "right":
                        rotation += 90;
                        this.canvasCxt.translate(this.canvas.width, 0);
                        break;
                    default:
                        console.error("Only left and right supported right now");
                        break;
                }
                // rotate the canvas to the specified degrees
                this.canvasCxt.rotate(rotation * Math.PI / 180);
                //_swapCanvasDimensions.apply(this);
                _draw.apply(this);

                // we’re done with the rotating so restore the unrotated context
                this.canvasCxt.restore();

            },
            resetFilters: function() {
                this.filters = [];
                this.applyFilter();
            }
        }
    })();

    return canvasUtils;
});