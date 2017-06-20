<?php

namespace AppBundle\Command;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProducerCommand extends Command
{
    /**
     * @var Producer
     */
    protected $producer;

    /**
     * Creates a new {\AppBundle\Command\ProducerCommand}.
     *
     * @param Producer $producer
     */
    public function __construct(Producer $producer)
    {
        parent::__construct();
        $this->producer = $producer;
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('workshop:producer');
    }

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Producing a message...');

        /** @var \OldSound\RabbitMqBundle\RabbitMq\Producer $producer */
        // $producer = $this->getContainer()->get('old_sound_rabbit_mq.direct_producer');

        $data = 'Hello world!';

        $this->producer->publish($data);
    }
}
