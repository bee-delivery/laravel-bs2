<?php

namespace BeeDelivery\Bs2\Utils;

use Illuminate\Support\Facades\Validator;

trait Helpers
{
    /*
     * Valida chave pix.
     *
     * @paran array $key
     * @return void
     */
    public function validatePixKey($key)
    {
        $validator = Validator::make($key, [
            'chave.id' => 'nullable|string',
            'chave.apelido' => 'nullable|string',
            'chave.valor' => 'required|string',
            'chave.tipo' => 'required|string'

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /*
     * Valida dados para confirmação de pagamento.
     *
     * @paran array $data
     * @return void
     */
    public function validateConfirmPaymentData($data)
    {
        $validator = Validator::make($data, [
            'recebedor.ispb' => 'required|string',

            'recebedor.conta.banco' => 'required|string',
            'recebedor.conta.bancoNome' => 'required|string',
            'recebedor.conta.agencia' => 'required|string',
            'recebedor.conta.numero' => 'required|string',
            'recebedor.conta.tipo' => 'required|string',

            'recebedor.pessoa.documento' => 'required|string',
            'recebedor.pessoa.tipoDocumento' => 'required|string',
            'recebedor.pessoa.nome' => 'required|string',
            'recebedor.pessoa.nomeFantasia' => 'required|string',

            'valor' => 'required|integer',
            'campoLivre' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /*
     * Valida dados para criação de cobrança dinâmica.
     *
     * @paran array $data
     * @return void
     */
    public function validateDynamicChargeData($data)
    {
        $validator = Validator::make($data, [
            'txId' => 'required|string',
            'cobranca.calendario.expiracao' => 'required|integer',
            'cobranca.devedor.cpf' => 'nullable|string',
            'cobranca.devedor.cnpj' => 'nullable|string',
            'cobranca.devedor.nome' => 'required|string',
            'cobranca.valor.original' => 'required|integer',
            'cobranca.chave' => 'required|string',
            'cobranca.infoAdicionais.nome' => 'nullable|string',
            'cobranca.infoAdicionais.valor' => 'nullable|string',
            'aceitaMaisDeUmPagamento' => 'nullable|boolean',
            'recebivelAposVencimento' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
}