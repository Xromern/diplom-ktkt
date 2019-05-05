'use strict';

class Preloader {

    constructor(block,src) {
        this.src = src;
        this.block = $(block);
        block.hide();
        block.html('<img src='+src+' >');

    }
    showPeloader() {
        this.block.show();
    }

     hidePrloaer() {
        this.block.hide();
    }

}
