import { GestorIsActiveElementInlist  } from "./gestor-is-active-element-list.js";
import { GestorLocalStorage } from "./gestor-local-storage.js";
import { GestorProduto } from "./gestor-produto.js";
import { GestorSlide } from "./gestor-slide.js";

( async () => {
    const gestorStorage = new GestorLocalStorage();
    
    document.querySelector("#valor-carrinho").textContent = gestorStorage.getSubtotalProdutos();

    const dialogMenu = document.querySelector(".dialog-menu");
    document.querySelector("#abrir-menu").addEventListener( "click", () => {
        dialogMenu.showModal();
    })
    document.querySelector("#fechar-menu").addEventListener( "click", () => {
        dialogMenu.close();
    })
    // document.querySelector(".li-carrinho").addEventListener( "click", () => {
    //     window.location.href = "./carrinho.html";
    // })
    
    
    // Underline nav links 
    let nav = document.querySelector("nav");
    nav.addEventListener( "click", ( event ) => {
        let element = event.target;
        if( element.tagName === "A" ) {
            let gestorElemento = new GestorIsActiveElementInlist(element, document.querySelector("#nav-links").querySelectorAll("li"));
            gestorElemento.disableClassFromAll( "is-active-a-underline" );
            gestorElemento.isActive( "is-active-a-underline" );
        }
    })


    // Banner Slide
    const btnBannerSlideList = document.querySelectorAll(".btn-banner-slide");
    const bannerSlideList = document.querySelectorAll(".banner-slide");
    const divBannerSlides = document.querySelector("#div-banner-slides");
    const gestorBannerSlides = new GestorSlide( divBannerSlides, bannerSlideList, btnBannerSlideList, "banner-slide-ativo", "btn-banner-slide-ativo");
    gestorBannerSlides.eventClickBotoes();


    // Banner Slide AUTOMÁTICO
    // setInterval(() => {
    //     gestorBannerSlides.passarSlide();
    // }, 3000);

    
    // Gestor Lançamentos
    const setaVoltarLancamento = document.querySelector("#seta-voltar-lancamento");
    const setaPassarLancamento = document.querySelector("#seta-passar-lancamento");
    const btnVerTodosLancamentos = document.querySelector("#ver-todos-lancamentos");
    const divBotoesLancamento = document.querySelector("#botoes-lancamentos");
    const divNavegacaoLancamentos = document.querySelector("#navegacao-lancamentos");
    const divSlidesLancamentos = document.querySelector("#lancamentos");
    const divLancamentos = document.querySelector("#div-lancamentos")
    const gestorLancamentos = new GestorProduto( setaVoltarLancamento, setaPassarLancamento, btnVerTodosLancamentos, divBotoesLancamento, divNavegacaoLancamentos, divSlidesLancamentos, divLancamentos, "lancamentos" );
    await gestorLancamentos.montarProdutos();
    await gestorLancamentos.carregarAcoes();
    
    // Gestor Destaques 
    const setaVoltarDestaque = document.querySelector("#seta-voltar-destaque");
    const setaPassarDestaque = document.querySelector("#seta-passar-destaque");
    const btnVerTodosDestaques = document.querySelector("#ver-todos-destaques");
    const divBotoesDestaque = document.querySelector("#botoes-destaques");
    const divNavegacaoDestaques = document.querySelector("#navegacao-destaques");
    const divSlidesDestaques = document.querySelector("#destaques");
    const divDestaques = document.querySelector("#div-destaques")
    const gestorDestaques = new GestorProduto( setaVoltarDestaque, setaPassarDestaque, btnVerTodosDestaques, divBotoesDestaque, divNavegacaoDestaques, divSlidesDestaques, divDestaques, "destaques" );
    await gestorDestaques.montarProdutos();
    await gestorDestaques.carregarAcoes();


    // Gestor Outlet
    const setaVoltarOutlet = document.querySelector("#seta-voltar-outlet");
    const setaPassarOutlet = document.querySelector("#seta-passar-outlet");
    const btnVerTodosOutlets = document.querySelector("#ver-todos-outlet");
    const divBotoesOutlet = document.querySelector("#botoes-outlet");
    const divNavegacaoOutlets = document.querySelector("#navegacao-outlet");
    const divSlidesOutlets = document.querySelector("#outlet");
    const divOutlets = document.querySelector("#div-outlet")
    const gestorOutlet = new GestorProduto( setaVoltarOutlet, setaPassarOutlet, btnVerTodosOutlets, divBotoesOutlet, divNavegacaoOutlets, divSlidesOutlets, divOutlets, "outlet" );
    await gestorOutlet.montarProdutos();
    await gestorOutlet.carregarAcoes();


    // Slide Depoimentos
    const divSlidesDepoimentos = document.querySelector("#depoimentos");
    const depoimentosSlideList = document.querySelectorAll(".slide-depoimentos");
    const divBotoesDepoimentos = document.querySelector(".botoes-depoimentos");
    let btnDepoimentosList;
    let gestorSlidesDepoimentos;

    
    // Criando um botão para cada Depoimento
    if( depoimentosSlideList.length > 1 ) {
        for( let i = 0; i < depoimentosSlideList.length; i++ ) {
            const btn = document.createElement("div");
            if( i == 0 ) {
                btn.classList.add("btn-depoimento", "btn-depoimento-ativo");
            }
            else {
                btn.classList.add("btn-depoimento");
            }
            divBotoesDepoimentos.appendChild(btn);
        }
        btnDepoimentosList = document.querySelectorAll(".btn-depoimento");
        gestorSlidesDepoimentos = new GestorSlide( divSlidesDepoimentos, depoimentosSlideList, btnDepoimentosList, "slide-depoimentos-ativo", "btn-depoimento-ativo", "surgir", "surgir");
    }
    gestorSlidesDepoimentos.eventClickBotoes();


    // Depoimento Slide AUTOMÁTICO
    // setInterval(() => {
    //     gestorSlidesDepoimentos.passarSlide();
    // }, 5000);
})()