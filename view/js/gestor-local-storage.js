
export class GestorLocalStorage {

    getTodos () {
        let produtos = [];
        for (let pos = 0; pos < localStorage.length; pos++) {
            const key = localStorage.key(pos);
            const produto = JSON.parse(localStorage.getItem(key));
            produtos.push(produto);        
        }
        return produtos;
    }


    getSubtotalProdutos () {
        let subtotal = 0;
        for (let pos = 0; pos < localStorage.length; pos++) {
            const key = localStorage.key(pos);
            const produto = JSON.parse(localStorage.getItem(key));
            const preco = produto.preco;
            subtotal += preco;
        }
        return subtotal;
    }


    adicionarProduto( produto ) {
        const produtoText = JSON.stringify(produto);
        window.localStorage.setItem( produto.nome, produtoText );
    }
}