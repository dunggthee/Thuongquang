function gravarPermissoes()
{
    include '../dao/ConnectionHolder.php';
    $idUsuario = $_POST["idUsuario"];
    if (!$idUsuario) {
        finalize("idUsuario não fornecido!");
    }
    $idList = $_POST["list"];
    if (!$idList) {
        finalize("Lista de permissões vazia!");
    }
    try {
        $connection = ConnectionHolder::getConnection();
        // PROBLEMA!!
        // O postgres não trabalha com o conceito de transação, commit e rollback,
        // ao invés disso, deve ser enviada uma query, sendo todo o script sql,
        // e será executado todo, ou nada.
        // Fonte: http://stackoverflow.com/questions/9704557/php-pgsql-driver-and-autocommit
        // Apaga as permissoes existentes para o usuário
        $query = "DELETE FROM permissoes WHERE idusuario = " . $idUsuario . "; ";
        // Insere as operações recebidas por POST
        foreach ($idList as $id) {
            $query .= "INSERT INTO permissoes (idoperacaomodulo, idusuario) " . "VALUES (" . $id . ", " . $idUsuario . "); ";
        }
        $resultSet = pg_query($connection, $query);
        if (!$resultSet) {
            finalize("Query error");
        }
        done();
    } catch (Exception $e) {
        finalize("Exception: " . $e->getMessage());
    }
}
