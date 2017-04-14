<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 17:22
 */

namespace Wwtg99\JsonRpc\Provider;


use Illuminate\Http\Request;
use Wwtg99\JsonRpc\Server\RequestFactory;

class JsonRpcRouter
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function parse(Request $request)
    {
        $fa = new RequestFactory();
        $cont = $request->getContent();
        if ($cont) {
            $req = $fa->parse($cont);
        } else {
            $req = $fa->parse($request->all());
        }
        $handler = resolve('ProcessHandler');
        $res = $handler->execute($req)->getResponseArray();
        return response()->json($res, 200, [], JSON_UNESCAPED_UNICODE);
    }
}