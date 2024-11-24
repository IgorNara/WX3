<?php
declare(strict_types = 1);


enum FormaPagamento: string {
    case Pix = "Pix";
    case Boleto = "Boleto";
    case Cartao = "Cartao";
}
?>