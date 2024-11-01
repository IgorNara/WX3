export class GestorSlide {
    posSlide = 0;
    divSlides;
    slideList;
    btnSlideList;
    slideClassAtivo;
    btnClassAtivo;
    nomeAnimacaoPassarSlide;
    nomeAnimacaoVoltarSlide;


    constructor ( divSlides, slideList, btnSlideList, slideClassAtivo, btnClassAtivo, nomeAnimacaoPassarSlide = "", nomeAnimacaoVoltarSlide = "" ) {
        this.divSlides = divSlides;
        this.slideList = slideList;
        this.btnSlideList = btnSlideList;
        this.slideClassAtivo = slideClassAtivo;
        this.btnClassAtivo = btnClassAtivo;
        this.nomeAnimacaoPassarSlide = nomeAnimacaoPassarSlide;
        this.nomeAnimacaoVoltarSlide = nomeAnimacaoVoltarSlide;
        this.eventTouch()
    }


    eventClickBotoes () {
        this.btnSlideList.forEach( ( btn, pos ) => {
            btn.addEventListener( "click", () => {
                this.posSlide = pos;
                this.ativarPosicaoSlide( pos, this.slideClassAtivo, this.slideList );
                this.ativarPosicaoBtnSlide( pos, this.btnClassAtivo, this.btnSlideList );
            });
        });
    }


    /**
     * Aplica a classe de ativação para apenas a posição de slide indicada.
     * @param {int} pos 
     * @param {string} classe
     * @param {NodeList} slideList
     * @returns {void}
     */
    ativarPosicaoSlide ( pos ) {
        this.disableClassFromAllSlide( this.slideClassAtivo, this.slideList );
        this.slideList[pos].classList.add( this.slideClassAtivo );
    }


    /**
     * Aplica a classe de ativação para apenas a posição de botão indicada.
     * @param {int} pos 
     * @param {string} classe
     * @param {NodeList} btnSlideList
     * @returns {void}
     */
    ativarPosicaoBtnSlide ( pos ) {
        this.disableClassFromAllBtn( this.btnClassAtivo, this.btnSlideList );
        this.btnSlideList[pos].classList.add( this.btnClassAtivo );
    }


    /**
     * Retira a classe de ativação de todos os botões.
     * @param {string} classe 
     * @param {NodeList} btnSlideList
     * @returns {void}
     */
    disableClassFromAllBtn () {
        this.btnSlideList.forEach( ( btn ) => {
            btn.classList.remove( this.btnClassAtivo );
        } )
    }


    /**
     * Retira a classe de ativação de todos os slides.
     * @param {string} classe 
     * @param {NodeList} slideList
     * @returns {void}
     */
    disableClassFromAllSlide () {
        this.slideList.forEach( ( slide ) => {
            slide.classList.remove( this.slideClassAtivo );
        } )
    }   


    /**
     * Deixa todos os slides visíveis.
     * @param {string} classe 
     * @param {NodeList} slideList 
     * @returns {void}
     */
    ativarTodos () {
        this.slideList.forEach( slide => {
            slide.classList.add( this.slideClassAtivo );
        })
    }


    ativarPrimeiroSlide () {
        this.posSlide = 0;
        this.ativarPosicaoSlide( this.posSlide, this.slideClassAtivo, this.slideList );
        this.ativarPosicaoBtnSlide( this.posSlide, this.btnClassAtivo, this.btnList );
    }


    passarSlide () {
        this.posSlide += 1;
        if( this.posSlide >= this.slideList.length ) {
            this.posSlide = 0;
        }
        this.slideList[this.posSlide].style.animationName = this.nomeAnimacaoPassarSlide;
        this.ativarPosicaoSlide( this.posSlide, this.slideClassAtivo, this.slideList );
        this.ativarPosicaoBtnSlide( this.posSlide, this.btnClassAtivo, this.btnSlideList );
    }


    voltarSlide () {
        this.posSlide -= 1;
        if( this.posSlide < 0 ) {
            this.posSlide = this.slideList.length - 1;
        }
        this.slideList[this.posSlide].style.animationName = this.nomeAnimacaoVoltarSlide;
        this.ativarPosicaoSlide( this.posSlide, this.slideClassAtivo, this.slideList );
        this.ativarPosicaoBtnSlide( this.posSlide, this.btnClassAtivo, this.btnSlideList );
    }


    eventTouch () {
        let touchstart;
        let touchend;

        this.divSlides.addEventListener("touchstart", (event) => {
            touchstart = event.touches[0].clientX;
        });

        this.divSlides.addEventListener("touchend", (event) => {
            touchend = event.changedTouches[0].clientX;
            if( touchstart - touchend > 70 ) {
                this.passarSlide();
            }
            else if ( touchend - touchstart > 70 ) {
                this.voltarSlide();
            }
        })
    }
}