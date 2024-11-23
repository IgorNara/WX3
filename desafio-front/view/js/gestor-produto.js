import { GestorSlide } from "./gestor-slide.js";

export class GestorProduto {
    gestorSlide;
    btnList;

    slideList;
    divBotoes;
    setaPassar;
    setaVoltar;
    btnVerTodos;
    gestorSlide;
    divNavegacao;
    divSlidesProduto;
    divProdutos;
    topicoProduto;


    constructor ( setaVoltar, setaPassar, btnVerTodos, divBotoes, divNavegacao, divSlidesProduto, divProdutos, topicoProduto ) {
        this.setaVoltar = setaVoltar;
        this.setaPassar = setaPassar;
        this.btnVerTodos = btnVerTodos;
        this.divBotoes = divBotoes;
        this.divNavegacao = divNavegacao;
        this.divSlidesProduto = divSlidesProduto;
        this.divProdutos = divProdutos;
        this.topicoProduto = topicoProduto;
    }


    carregarAcoes () {
        this.criarBotoesSlides();
        this.clickSetaPassarSlide();
        this.clickSetaVoltarSlide();
        this.clickEspiar();
        this.clickBtnComprar();
        this.clickBtnVerTodos();
        this.gestorSlide.ativarPrimeiroSlide();
    }


    /**
     * Se quantidade de slides > 1, cria um botão para cada slide presente e adiciona na div de botões.
     * @param {NodeList} slideList 
     * @param {string} classe 
     * @returns {void}
     */
    criarBotoesSlides () {
        if ( this.slideList.length > 1 ) {
            this.setaVoltar.style.display = "block";
            this.setaPassar.style.display = "block";
            this.btnVerTodos.style.display = "block";
            for( let i = 0; i < this.slideList.length; i++ ) {
                const btn = document.createElement( "div" );
                if( i == 0 ) {
                    btn.classList.add("btn-produto", "btn-produto-ativo");
                }
                else {
                    btn.classList.add("btn-produto");
                }
                this.divBotoes.appendChild(btn);
            }
            this.btnList = this.divBotoes.querySelectorAll("div");
            this.gestorSlide = new GestorSlide( this.divSlidesProduto, this.slideList, this.btnList, "slide-produto-ativo","btn-produto-ativo", "passar", "voltar" );
        }
    }


    clickEspiar () {
        const btnEspiarList = this.divSlidesProduto.querySelectorAll(".espiar");
        btnEspiarList.forEach( btn => {
            btn.addEventListener( "click", () => {
                window.location.href = `./produto.html?id=${btn.getAttribute("idProduto")}&topico=${btn.getAttribute("topicoProduto")}`;
            })
        })
    }


    clickBtnComprar () {
        const btnComprarList = this.divSlidesProduto.querySelectorAll("button");
        btnComprarList.forEach( btn => {
            btn.addEventListener( "click", () => {
                window.location.href = `./produto.html?id=${btn.getAttribute("idProduto")}&topico=${btn.getAttribute("topicoProduto")}`;
            })
        })
    }


    clickSetaPassarSlide () {
        this.setaPassar.addEventListener( "click", () => {
            this.gestorSlide.passarSlide();
        });
    }


    clickSetaVoltarSlide () {
        this.setaVoltar.addEventListener( "click", () => {
            this.gestorSlide.voltarSlide();
        });
    }


    clickBtnVerTodos () {
        this.btnVerTodos.addEventListener( "click", (event) => {
            event.preventDefault(); 

            const tituloProdutos = this.divProdutos.querySelector(".titulo-produtos");

            this.gestorSlide.ativarPrimeiroSlide();

            this.slideList.forEach( slide => {
                slide.style.animationName = "none";
                slide.style.marginBottom= "1.5vw";
            })

            if( this.btnVerTodos.textContent == "Ver menos") {
                this.btnVerTodos.innerHTML = "<span>Ver</span> todos";
                this.btnVerTodos.style.marginTop = "1.5vw";
                this.divNavegacao.style.display = "flex";
                this.slideList.forEach( slide => {
                    slide.style.marginBottom= "0";
                })
                tituloProdutos.style.marginBottom = "10px";
                window.location.href = "#" + this.divProdutos.getAttribute("id");
            }
            else {
                tituloProdutos.style.marginBottom = "1.5vw";
                this.btnVerTodos.innerHTML = "<span>Ver</span> menos";
                this.btnVerTodos.style.marginTop = "0";
                this.gestorSlide.ativarTodos();
                this.divNavegacao.style.display = "none";
            }
        });
    }


    async getProdutos () {
        return await fetch( "./produtos.json" )
        .then( resposta => {
            if( !resposta.ok ) {
                throw new Error( "Erro ao consultar produtos." );
            }
            return resposta.json();
        })
        .catch ( e => console.log( e ));
    }


    async montarProdutos () {
        await this.getProdutos().then( resposta => {
            resposta[this.topicoProduto].forEach( slide => {
                const slideProduto = document.createElement("div");
                slideProduto.classList.add( "slide-produto", `slide-${this.topicoProduto}` );
                slide.forEach( produto => {
                    const cardProduto = this.cardProduto( produto );
                    slideProduto.appendChild(cardProduto);
                })
                this.divSlidesProduto.appendChild(slideProduto);
            })
            this.slideList = this.divSlidesProduto.querySelectorAll(".slide-produto");
        })
    }


    cardProduto ( { id, nome, preco, estrelas, url_img } ) {
        const divProduto = document.createElement("div");
        divProduto.classList.add("produto");
        if(this.topicoProduto == "lancamentos") {
            const h4 = document.createElement("h4");
            h4.textContent = "Lançamento";
            divProduto.appendChild(h4);
        }

        // Div ImgProduto
        const divImgProduto = document.createElement("div");
        divImgProduto.classList.add("div-img-produto");
        divImgProduto.style.backgroundImage = `url(${url_img})`;
        const pFavoritarEspiar = document.createElement("p");
        pFavoritarEspiar.innerHTML = `
            <span class="favoritar" idProduto="${id}" topicoProduto="${this.topicoProduto}">
                <img src="./view/imagens-icones/icon-coracao-branco-30px.png" alt="Favoritar"> Favoritar
            </span>
            |
            <span class="espiar" idProduto="${id}" topicoProduto="${this.topicoProduto}">
                <img src="./view/imagens-icones/icon-olho-branco-30px.png" alt="Espiar"> Espiar
            </span>`;
        divImgProduto.appendChild(pFavoritarEspiar);

        // Div Info Produto
        const divInfoProduto = document.createElement("div");
        divInfoProduto.classList.add("info-produto");

        // Div Descrição
        const divDescricao = document.createElement("div");
        const divEstrelas = document.createElement("div");
        divEstrelas.classList.add("produto-estrelas");
        for( let i = 0; i < estrelas; i++ ) {
            const imgEstrela = document.createElement("img");
            imgEstrela.src = "./view/imagens-icones/icon-estrela-30px.png";
            imgEstrela.alt = "Estrela";
            divEstrelas.appendChild(imgEstrela);
        }
        const pNomeProduto = document.createElement("p");
        pNomeProduto.setAttribute("id", "nome-produto");
        pNomeProduto.textContent = nome;
        const pRef = document.createElement("p");
        pRef.textContent = "ref.: pdc202";
        divDescricao.append( divEstrelas, pNomeProduto, pRef );

        // Div Preço
        const divPreco = document.createElement("div");
        const pPreco = document.createElement("p");
        pPreco.classList.add("preco-content");
        pPreco.innerHTML = `R$ <span id="preco">${preco}</span> <br> <span>no boleto</span>`;
        const pParcelas = document.createElement("p");
        pParcelas.innerHTML = `em até 10x de R$ <span id="preco-parcelado">${((preco*1.1)/10).toFixed(2)} </span>`; 
        divPreco.append( pPreco, pParcelas );

        // Btn Comprar
        const btnComprar = document.createElement("button");
        btnComprar.setAttribute("idProduto", id);
        btnComprar.setAttribute("topicoProduto", this.topicoProduto);
        btnComprar.textContent = "COMPRE AGORA";

        divInfoProduto.append( divDescricao, divPreco, btnComprar );
        divProduto.append( divImgProduto, divInfoProduto );
        return divProduto;
    }
}