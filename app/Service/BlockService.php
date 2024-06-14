<?php
namespace App\Service;
interface BlockService 
{
    function getAllBlocks(): array; 
    function getBlock(int $id): array;
    function store(array $data): string;
    function update(array $data, int $id): string; 
    function delete(int $id): string; 
}