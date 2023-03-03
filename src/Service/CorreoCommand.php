<?php 

    namespace App\Service;


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;


    #[AsCommand(
        name: 'app:admin:correo',
        description: 'Manda un correo',
        hidden: false,
        aliases: ['app:admin:correo']
    )]

    class CorreoCommand extends Command
    {

        private mail $mail;
        
        public function __construct(mail $mail)
        {
            parent::__construct();
            $this->mail =$mail;

        }

        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            //MANDA UN CORREO AL USUARIO
            $this->mail->sendEmail('alexo.02@hotmail.com');
            return Command::SUCCESS;
        }

        
    }

?>