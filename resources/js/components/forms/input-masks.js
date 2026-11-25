/**
 * Máscaras de digitação (Inputmask): <input data-mascara="cpf">. Só formata o que aparece na tela;
 * o servidor recebe os números e confere (regras do pacote de validação brasileiro), com ou sem máscara.
 *
 * - cpf:      000.000.000-00
 * - telefone: (00) 0000-0000 ou (00) 00000-0000, conforme o número de dígitos
 * - ip:       000.000.000.000 (só o formato; o servidor confere a faixa 0 a 255)
 *
 * O atributo é data-mascara, e não data-inputmask: o Inputmask lê data-inputmask como opções em JSON, e um valor
 * como "cpf" lança erro ao montar a máscara (o que interrompia o módulo inteiro da página).
 */
import Inputmask from 'inputmask';

const MASCARAS = {
    cpf: { mask: '999.999.999-99' },
    telefone: { mask: ['(99) 9999-9999', '(99) 99999-9999'], keepStatic: true },
    ip: { mask: '9{1,3}.9{1,3}.9{1,3}.9{1,3}' },
};

document.querySelectorAll('input[data-mascara]').forEach((campo) => {
    const opcoes = MASCARAS[campo.dataset.mascara];

    if (opcoes) {
        Inputmask(opcoes).mask(campo);
    }
});
