<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 17:03
 */

namespace Wwtg99\JsonRpc\Provider;


use Illuminate\Http\Request;
use Wwtg99\JsonRpc\Server\RequestFactory;

trait JsonRpcRequestTrait
{

    /**
     * @param Request $request
     * @return array
     */
    public function parseJsonRpc(Request $request)
    {
        $fa = new RequestFactory();
        $cont = $request->getContent();
        if ($cont) {
            $req = $fa->parse($cont);
        } else {
            $req = $fa->parse($request->all());
        }
        $handler = resolve('ProcessHandler');
        return $handler->execute($req)->getResponseArray();
    }
}