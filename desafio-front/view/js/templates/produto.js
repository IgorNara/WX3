import { GestorLocalStorage } from "../gestor-local-storage.js";



( async () => {
    const gestorStorage = new GestorLocalStorage();
    const dialogMenu = document.querySelector(".dialog-menu");

    document.querySelector("#valor-carrinho").textContent = gestorStorage.getSubtotalProdutos();

    document.querySelector("#abrir-menu").addEventListener( "click", () => {
        dialogMenu.showModal();
    })
    document.querySelector("#fechar-menu").addEventListener( "click", () => {
        dialogMenu.close();
    })
    // document.querySelector(".li-carrinho").addEventListener( "click", () => {
    //     window.location.href = "./carrinho.html";
    // })


    const url = new URL(window.location.href);
    const paramsUrl = new URLSearchParams(url.search);
    const respostaProduto = await getProduto( paramsUrl.get("id"), paramsUrl.get("topico") );
    carregarInformacoesProduto( respostaProduto );


    document.querySelector("button").addEventListener( "click", () => {
        gestorStorage.adicionarProduto( respostaProduto );
        alert("Produto adicionado ao carrinho.");
        window.location.href = "./index.html";
    })
})()


async function getProduto( id, topicoProduto ) {
    return await fetch( "./produtos.json" )
    .then( resposta => {
        if( !resposta.ok ) {
            throw new Error( "Erro ao consultar produtos." );
        }
        return resposta.json();
    })
    .then( resposta => {
        let produto;
        resposta[topicoProduto].forEach( slideProduto => {
            if(slideProduto.find( produto => produto.id == id))
                produto = slideProduto.find( produto => produto.id == id);
        });
        return produto;
    })
    .catch( e => console.log(e) );
}


function carregarInformacoesProduto( produto ) {
    document.querySelector("#nome").textContent = produto.nome;
    document.querySelector("#preco").textContent = produto.preco;
    document.querySelector("#preco-parcelado").textContent = ((produto.preco*1.1)/10).toFixed(2);
    document.querySelector("#img").src = produto.url_img;
}