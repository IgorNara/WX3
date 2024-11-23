<?php
declare(strict_types = 1);


enum FormaPagamentoDesconto: int {
    case Pix = 10;
    case Boleto = 0;
    case Cartao = 0;
}
?>