<?php

echo "<h3>CLIENTE</h3>";
echo password_hash("cliente123", PASSWORD_DEFAULT);

echo "<hr>";

echo "<h3>ADMINISTRADOR</h3>";
echo password_hash("123456", PASSWORD_DEFAULT);

echo "<hr>";

echo "<h3>DISEÑADOR</h3>";
echo password_hash("domo123", PASSWORD_DEFAULT);