<?php
namespace src\Contract;

interface Renderable {
  public function render(): string;
}