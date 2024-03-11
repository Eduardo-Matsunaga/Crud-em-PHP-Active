<?php

require_once 'player.php';
require_once 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use pdo_poo\Database;

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';

if ($requestMethod === 'POST') {
    $action = $_POST['action'];
    $playersRows = '';


    switch ($action) {
        case 'create':
            $id = $_POST['id'];
            $name = $_POST['name'];
            $userName = $_POST['userName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $dateRegister = date('d-m-y');

            $newPlayer = new player($id,$name, $userName, $email, $password, $dateRegister);
            $newPlayer->setName($name);
            $newPlayer->setUserName($userName);
            $newPlayer->setEmail($email);
            $newPlayer->setPassword($password);
            $newPlayer->setDateRegister($dateRegister);
            $newPlayer->savePlayer();
            break;

        case 'edit':
            $idToEdit = $_POST['id'];
            // Cria uma instância de Player com valores padrão
            $playerToEdit = new player();
            // Obtém os dados do jogador existente do banco de dados
            $existingPlayer = $playerToEdit->getPlayerById($idToEdit);

            // Atualiza a instância com os dados existentes
            $playerToEdit->setId($existingPlayer->getId());
            $playerToEdit->setName($existingPlayer->getName());
            $playerToEdit->setUserName($existingPlayer->getUserName());
            $playerToEdit->setEmail($existingPlayer->getEmail());
            $playerToEdit->setPassword($existingPlayer->getPassword());
            $playerToEdit->setDateRegister($existingPlayer->getDateRegister());

            $template = file_get_contents(__DIR__.'/editPlayer.html');
            $template = str_replace('{{id}}', $playerToEdit->getId(), $template);
            $template = str_replace('{{name}}', $playerToEdit->getName(), $template);
            $template = str_replace('{{userName}}', $playerToEdit->getUserName(), $template);
            $template = str_replace('{{email}}', $playerToEdit->getEmail(), $template);
            $template = str_replace('{{password}}', $playerToEdit->getPassword(), $template);
            $template = str_replace('{{dateRegister}}', $playerToEdit->getDateRegister(), $template);

            echo $template;
            break;

        case 'update':
            $idToUpdate = $_POST['id'];
            $playerToUpdate = new player();

            // Obtém os dados do jogador existente do banco de dados
            $existingPlayer = $playerToUpdate->getPlayerById($idToUpdate);

            $playerToUpdate->setId($existingPlayer->getId());
            $playerToUpdate->setName($_POST['name'] ?? '');
            $playerToUpdate->setUserName($_POST['userName'] ?? '');
            $playerToUpdate->setEmail($_POST['email'] ?? '');
            $playerToUpdate->setPassword($_POST['password'] ?? '');
            $playerToUpdate->setDateRegister($_POST['dateRegister'] ?? '');
            $playerToUpdate->savePlayer();
            break;

        case 'confirmDelete':
            $idToEdit = $_POST['id'];

            // Cria uma instância de Player com valores padrão
            $playerToEdit = new player();

            // Obtém os dados do jogador existente do banco de dados
            $existingPlayer = $playerToEdit->getPlayerById($idToEdit);

            // Atualiza a instância com os dados existentes
            $playerToEdit->setId($existingPlayer->getId());
            $playerToEdit->setName($existingPlayer->getName());
            $playerToEdit->setUserName($existingPlayer->getUserName());
            $playerToEdit->setEmail($existingPlayer->getEmail());
            $playerToEdit->setPassword($existingPlayer->getPassword());
            $playerToEdit->setDateRegister($existingPlayer->getDateRegister());

            $template = file_get_contents(__DIR__.'/delete.html');
            $template = str_replace('{{id}}', $playerToEdit->getId(),$template);
            $template = str_replace('{{name}}', $playerToEdit->getName(), $template);
            $template = str_replace('{{userName}}', $playerToEdit->getUserName(), $template);
            $template = str_replace('{{email}}', $playerToEdit->getEmail(), $template);
            $template = str_replace('{{password}}', $playerToEdit->getPassword(), $template);
            $template = str_replace('{{dateRegister}}', $playerToEdit->getDateRegister(), $template);

            echo $template;

            break;

        case 'delete':
            $idToDelete = $_POST['id'];
            $playerToDelete = new player();
            $playerToDelete->setId($idToDelete);
            $playerToDelete->deletePlayer();
            break;

        case 'listAll':
            $players = player::listAll();
            $playersRows = '';

            foreach ($players as $player) {
                $playersRows .= '<tr>';
                $playersRows .= '<td>'. $player->getId() .'</td>';
                $playersRows .= '<td>'. $player->getName() .'</td>';
                $playersRows .= '<td>'. $player->getUserName() .'</td>';
                $playersRows .= '<td>'. $player->getEmail() .'</td>';
                $playersRows .= '<td>'. $player->getPassword() .'</td>';
                $playersRows .= '<td>'. $player->getDateRegister() .'</td>';
                $playersRows .= '<td>';
                $playersRows .= '<form action="app.php" method="POST">';
                $playersRows .= '<input type="hidden" name="id" value="' . $player->getId() . '">';
                $playersRows .= '<button type="submit" name="action" value="edit">Editar</button>';
                $playersRows .= '<button type="submit" name="action" value="confirmDelete">Deletar</button>';
                $playersRows .= '</form>';
                $playersRows .= '</td>';
                $playersRows .= '</tr>';
            }
            $template = file_get_contents(__DIR__ .'/listPlayers.html');
            $template = str_replace('<!--AQUI_VEM_AS_LINHAS-->', $playersRows, $template);
            echo $template;
            break;
        default:
            break;
    }
}