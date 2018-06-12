<?php

require '../vendor/autoload.php';

require_once 'Secom.php';

$secom = new Secom();

$stop = false;

/**
 * pcntl_fork() - данная функция разветвляет текущий процесс
 */
$pid = pcntl_fork();
if ($pid == -1) {
    /**
     * Не получилось сделать форк процесса, о чем сообщим в консоль
     */
    die('Error fork process' . PHP_EOL);
} elseif ($pid) {
    /**
     * В эту ветку зайдет только родительский процесс, который мы убиваем и сообщаем об этом в консоль
     */
    die('Die parent process' . PHP_EOL);
} else {
    /**
     * Бесконечный цикл
     */
    while(!$stop) {
        print_r($secom->getFullListTikets());
    }
}
/**
 * Установим дочерний процесс основным, это необходимо для создания процессов
 */
posix_setsid();