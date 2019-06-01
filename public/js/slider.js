    // https://dribbble.com/shots/2658222

    const pathLength = 39;

    const BtnGroup = class BtnGroup {
        constructor(group) {
            this.buttonSpacing = 20;
            this.group = group;
            this.buttons = Array.prototype.slice.call(this.group.querySelectorAll('.btn'));
            this.slides = Array.prototype.slice.call(document.querySelectorAll('.slide'));
            this.slideContainer = document.querySelector('.slides');
            this.slideContainer.style.width = this.slides.length + '00vw';
            this.svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            this.svg.setAttribute('viewbox', '0 0 ' + (this.buttonSpacing * this.buttons.length) + ' 16');
            this.path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            this.path.classList.add('line');
            this.currentPath = 'M ' + (-this.buttonSpacing / 2) + ', 14';
            this.currentIndex = -1;
            this.activateIndex(this.buttons.indexOf(this.group.querySelector('.active')));
            this.group.appendChild(this.svg);
            this.svg.appendChild(this.path);
            this.refreshPath();
            this.initButtons();

            for (const button of this.buttons) {
                button.addEventListener('click', e => this.onClick(e));
            }
        }

        initButtons() {
            for (var i = 0; i < this.buttons.length; i++) {
                const center = this.center(i);
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                let pathStr = '';
                pathStr += 'M' + (center    ) + ', 14 ';
                pathStr += 'C' + (center + 3) + ', 14 ';
                pathStr +=       (center + 6) + ', 11 ';
                pathStr +=       (center + 6) + ',  8 ';
                pathStr += 'C' + (center + 6) + ',  5 ';
                pathStr +=       (center + 3) + ',  2 ';
                pathStr +=       (center    ) + ',  2 ';
                pathStr += 'C' + (center - 3) + ',  2 ';
                pathStr +=       (center - 6) + ',  5 ';
                pathStr +=       (center - 6) + ',  8 ';
                pathStr += 'C' + (center - 6) + ', 11 ';
                pathStr +=       (center - 3) + ', 14 ';
                pathStr +=       (center    ) + ', 14 ';
                path.setAttributeNS(null, 'd', pathStr);
                path.classList.add('circle');
            }
        }

        onClick(e) {
            const index = this.buttons.indexOf(e.srcElement || e.target);
            this.activateIndex(index);
        }

        refreshPath() {
            this.path.setAttributeNS(null, 'd', this.currentPath);
            this.path.style.strokeDashoffset = (-this.path.getTotalLength() + pathLength) * 0.9965;
        }

        center(index) {
            return index * this.buttonSpacing + (this.buttonSpacing / 2);
        }

        removeClass(str) {
            if (this.buttons[this.currentIndex]) {
                this.buttons[this.currentIndex].classList.remove(str);
            }
        }

        addClass(str) {
            if (this.buttons[this.currentIndex]) {
                this.buttons[this.currentIndex].classList.add(str);
            }
        }

        activateIndex(index) {
            this.slideContainer.style.left = -index + '00vw';
            const lastCenter = this.center(this.currentIndex);
            const nextCenter = this.center(index);
            const offset = 0;
            const sign = index < this.currentIndex ? -1 : 1;
            this.currentPath += 'C' + (lastCenter + sign * 3) + ', 14 ';
            this.currentPath +=       (lastCenter + sign * 6) + ', 11 ';
            this.currentPath +=       (lastCenter + sign * 6) + ',  8 ';
            this.currentPath += 'C' + (lastCenter + sign * 6) + ',  5 ';
            this.currentPath +=       (lastCenter + sign * 3) + ',  2 ';
            this.currentPath +=       (lastCenter           ) + ',  2 ';
            this.currentPath += 'C' + (lastCenter - sign * 3) + ',  2 ';
            this.currentPath +=       (lastCenter - sign * 6) + ',  5 ';
            this.currentPath +=       (lastCenter - sign * 6) + ',  8 ';
            this.currentPath += 'C' + (lastCenter - sign * 6) + ', 11 ';
            this.currentPath +=       (lastCenter - sign * 3) + ', 14 ';
            this.currentPath +=       (lastCenter           ) + ', 14 ';
            this.currentPath += 'L' + (nextCenter           ) + ', 14 ';
            this.currentPath += 'C' + (nextCenter + sign * 3) + ', 14 ';
            this.currentPath +=       (nextCenter + sign * 6) + ', 11 ';
            this.currentPath +=       (nextCenter + sign * 6) + ',  8 ';
            this.currentPath += 'C' + (nextCenter + sign * 6) + ',  5 ';
            this.currentPath +=       (nextCenter + sign * 3) + ',  2 ';
            this.currentPath +=       (nextCenter           ) + ',  2 ';
            this.currentPath += 'C' + (nextCenter - sign * 3) + ',  2 ';
            this.currentPath +=       (nextCenter - sign * 6) + ',  5 ';
            this.currentPath +=       (nextCenter - sign * 6) + ',  8 ';
            this.currentPath += 'C' + (nextCenter - sign * 6) + ', 11 ';
            this.currentPath +=       (nextCenter - sign * 3) + ', 14 ';
            this.currentPath +=       (nextCenter           ) + ', 14 ';
            this.removeClass('active');
            this.currentIndex = index;
            this.addClass('active');
            this.refreshPath();
        }
    }

    const groups = Array.prototype.slice.call(document.querySelectorAll('.btn-group'));

    for (const group of groups) {
        console.log(new BtnGroup(group));
    }