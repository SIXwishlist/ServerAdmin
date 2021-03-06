<?php

namespace Richardds\ServerAdmin\Core\Firewall;

use Richardds\ServerAdmin\Core\Commands\ServiceCommand;
use Richardds\ServerAdmin\Core\ConfigurableService;
use Richardds\ServerAdmin\Core\Service;
use Richardds\ServerAdmin\Facades\Execute;
use Richardds\ServerAdmin\FirewallRule;

class FirewallManager extends Service implements ConfigurableService
{
    const RULES_CONFIG_FILE = '/etc/ufw/user.rules';

    public function __construct()
    {
        parent::__construct('ufw');
    }

    /**
     * Reload service.
     */
    public function reload(): void
    {
        ServiceCommand::forceReload($this->name);
    }

    public function createRule(FirewallRule $rule)
    {
        $port = !is_null($rule->portTo) ? "{$rule->port}:{$rule->portTo}" : $rule->port;

        if (!is_null($rule->destination) && !is_null($rule->source)) {
            $command = "ufw {$rule->type} from {$rule->source} to {$rule->destination} port {$port} proto {$rule->protocol}";
        } else if (is_null($rule->destination) && !is_null($rule->source)) {
            $command = "ufw {$rule->type} from {$rule->source} port {$port} proto {$rule->protocol}";
        } else if (!is_null($rule->destination) && is_null($rule->source)) {
            $command = "ufw {$rule->type} to {$rule->destination} port {$port} proto {$rule->protocol}";
        } else {
            $command = "ufw {$rule->type} {$port}/{$rule->protocol}";
        }

        Execute::withoutOutput($command, true);
    }

    public function configure()
    {
        $rules = FirewallRule::enabled()->get();

        $this->clearRules();

        foreach ($rules as $rule) {
            $this->createRule($rule);
        }
    }

    public function clearRules()
    {
        $rules = Execute::output("ufw status numbered", true);
        $count = count(explode("\n", $rules)) - 6;

        while ($count > 0) {
            Execute::withoutOutput("echo y | ufw delete {$count}", true);
            $count--;
        }
    }
}
