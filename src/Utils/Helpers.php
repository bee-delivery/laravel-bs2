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
}
