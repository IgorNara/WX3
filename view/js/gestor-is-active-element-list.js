export class GestorIsActiveElementInlist {
    liList;
    element;

    constructor ( element, liList ) {
        this.element = element;
        this.liList = liList;
    }


    /**
     * Adiciona uma classe de ativação ao elemento.
     * @param {string} classe 
     * @returns {void}
     */
    isActive ( classe ) {
        this.element.classList.add(classe);
    }


    /**
     * Retira a classe de todos os elementos.
     * @param {string} classe 
     * @returns {void}
     */
    disableClassFromAll ( classe ) {
        this.liList.forEach( ( li ) => {
            let link = li.querySelector("a");
            link.classList.remove(classe);
        } )
    }
}