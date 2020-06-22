<?php declare(strict_types = 1);

namespace Reservio\Session\Aerospike;

use Aerospike;
use SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface
{

    private Aerospike $client;

    /**
     * @see https://www.aerospike.com/docs/operations/configure/namespace/
     */
    private string $namespace;

    /**
     * Aerospike key set/prefix, for. example "app1-sessions" under "production" namespace.
     */
    private string $set;

    private int $lifetimeMinutes;

    public const OPTIONS = [
        Aerospike::OPT_POLICY_EXISTS => Aerospike::POLICY_EXISTS_CREATE_OR_REPLACE,
        Aerospike::OPT_POLICY_KEY => Aerospike::POLICY_KEY_SEND
    ];



    public function __construct(Aerospike $client, string $namespace, string $set, int $lifetimeMinutes = 60)
    {
        $this->client = $client;
        $this->namespace = $namespace;
        $this->set = $set;
        $this->lifetimeMinutes = $lifetimeMinutes;
    }



    /** @inheritDoc */
    public function close()
    {
        return true;
    }



    /** @inheritDoc */
    public function destroy($session_id)
    {
        $result = $this->client->remove($this->getKey($session_id));

        // ignore error due to issue https://github.com/aerospike/aerospike-client-php/issues/6
        return $result === Aerospike::OK; // @phpstan-ignore-line
    }



    /** @inheritDoc */
    public function gc($maxlifetime)
    {
        return true;
    }



    /** @inheritDoc */
    public function open($save_path, $name)
    {
        return true;
    }



    /** @inheritDoc */
    public function read($session_id)
    {
        $response = [];
        $this->client->get($this->getKey($session_id), $response);

        return (string)($response['bins']['scv'] ?? '');
    }



    /** @inheritDoc */
    public function write($session_id, $session_data)
    {
        $result = $this->client->put(
            $this->getKey($session_id),
            ['scv' => $session_data],
            $this->lifetimeMinutes * 60, self::OPTIONS
        );

        // ignore error due to issue https://github.com/aerospike/aerospike-client-php/issues/6
        return $result === Aerospike::OK; // @phpstan-ignore-line
    }



    /**
     * @param string $key
     *
     * @return mixed[]
     */
    private function getKey(string $key): array
    {
        return $this->client->initKey($this->namespace, $this->set, $key);
    }

}
