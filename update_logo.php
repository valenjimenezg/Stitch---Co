<?php

$file = 'c:\xampp\htdocs\stitch-and-co\resources\views\welcome.blade.php';
$lines = file($file);
$newLines = array_merge(array_slice($lines, 0, 121), [
    "                    {{-- Stitch and Co Logo --}}\n",
    "                    <div class=\"flex h-full w-full items-center justify-center p-10\">\n",
    "                        <div class=\"w-64 h-64 lg:w-80 lg:h-80 rounded-full bg-white flex items-center justify-center shadow-[0_0_30px_rgba(0,0,0,0.1)] border border-[#e3e3e0] dark:border-[#3E3E3A] transition-transform duration-300 hover:scale-105 overflow-hidden\">\n",
    "                            <img src=\"{{ asset('logo.png') }}\" alt=\"Stitch and Co Logo\" class=\"w-[85%] h-[85%] object-contain\">\n",
    "                        </div>\n",
    "                    </div>\n",
], array_slice($lines, 267));
file_put_contents($file, implode('', $newLines));
