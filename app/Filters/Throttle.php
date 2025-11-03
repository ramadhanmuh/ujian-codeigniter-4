<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Throttle implements FilterInterface
{
    /**
     * This is a demo implementation of using the Throttler class
     * to implement rate limiting for your application.
     *
     * @param list<string>|null $requests
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $requests = null)
    {
        $throttler = service('throttler');

        $session = session();

        if ($session->has('user')) {
            $userKey = 'user:' . $session->get('id');
        } else {
            if (!$session->has('guest')) {
                $session->set('guest', bin2hex(random_bytes(16)));
            }

            $userKey = 'guest:' . $session->get('guest');
        }

        // batasi per session ID, misalnya jumlah $requests permintaan per menit
        if ($throttler->check(md5($userKey), $requests[0], MINUTE) === false) {
            return redirect()->back()
                                ->with('error', 'Terlalu banyak permintaan, silahkan tunggu beberapa saat lagi.');
        }

        return null;
    }

    /**
     * We don't have anything to do here.
     *
     * @param list<string>|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}