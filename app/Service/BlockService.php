<?php
namespace App\Service;
interface BlockService 
{
    function getAllBlocks(): array; 
    function getBlock(int $id): array;
}