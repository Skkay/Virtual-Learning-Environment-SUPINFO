<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create a new user';

    private $em;
    private $passwordHasher;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $doctrine->getManager();
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User\'s email')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'User\'s password')
            ->addOption('role', 'r', InputOption::VALUE_IS_ARRAY + InputOption::VALUE_OPTIONAL, 'User\'s role')
            ->addOption('no-password', null, InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getOption('password');
        $roles = $input->getOption('role');
        $noPassword = $input->getOption('no-password');

        if ($password !== null && $noPassword === true) {
            $io->error('A password can\'t be defined with --no-password option.');

            return Command::INVALID;
        }

        // If password is not defined via CLI, ask if user want a password
        if ($password === null && $noPassword === false) {
            $helper = $this->getHelper('question');

            if ($helper->ask($input, $output, new ConfirmationQuestion('Add a password ? [Y/n]'))) {
                $password = $helper->ask($input, $output, (new Question('Password:'))->setHidden(true)->setHiddenFallback(false));
            }
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);

        if ($password !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('User "%s" successfully created. Roles: %s. Password: %s.', $email, json_encode($roles), $password === null ? 'No' : 'Yes'));

        return Command::SUCCESS;
    }
}
