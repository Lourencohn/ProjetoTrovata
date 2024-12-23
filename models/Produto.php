<?php
class Produto {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllByEmpresa($empresa_id, $order = 'descricao', $limit = 15, $offset = 0) {
        $sql = "SELECT p.*, g.descricao as grupo_descricao 
                FROM produtos p 
                JOIN grupos_produtos g ON p.grupo_produto_id = g.id 
                WHERE p.empresa_id = ? 
                ORDER BY $order 
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $empresa_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($descricao, $apelido, $codigo, $empresa_id, $grupo_produto_id, $tipo_complemento) {
        $sql = "INSERT INTO produtos (descricao, apelido, codigo, empresa_id, grupo_produto_id, tipo_complemento) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssiss', $descricao, $apelido, $codigo, $empresa_id, $grupo_produto_id, $tipo_complemento);
        return $stmt->execute();
    }

    public function update($id, $descricao, $apelido, $codigo, $grupo_produto_id, $tipo_complemento) {
        $sql = "UPDATE produtos SET descricao = ?, apelido = ?, codigo = ?, grupo_produto_id = ?, tipo_complemento = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssisi', $descricao, $apelido, $codigo, $grupo_produto_id, $tipo_complemento, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>