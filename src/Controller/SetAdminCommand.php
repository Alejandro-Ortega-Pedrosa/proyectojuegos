<?php 

namespace App\Command;


use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(
    name: 'app:juez:set-roles',
    description: 'Creates a new juez.',
    hidden: false,
    aliases: ['app:juez:set-roles']
)]

class SetJuezCommand extends Command
{
    
    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userRepository->findOneByEmail('alexo.02@hotmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $this->userRepository->save($user,true);
        return Command::SUCCESS;
    }

    
}

?>