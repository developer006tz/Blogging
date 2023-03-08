<?php

namespace App;

include 'connect.php';

class Db {
    public static function executeQuery($sql, $data = [], $emulatePrepares = true) {
        global $conn;
        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, $emulatePrepares);
        $stmt = $conn->prepare($sql);

        try {
            if (empty($data)) {
                $stmt->execute();
            } else {
                $stmt->execute($data);
            }
            return $stmt;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    private static function getFillableData($rawData) {
        if (isset(static::$fillable) && count(static::$fillable) > 0) {
            $data = [];
            foreach (static::$fillable as $columnName) {
                if (array_key_exists($columnName, $rawData)) {
                  $data[$columnName] = $rawData[$columnName];  
                }
            }
            return $data;
        }
        return $rawData;
    }

    public static function selectAll($conditions = [], $conjunction = "AND") {
        $sql = "SELECT * FROM " . static::$tableName;


        if (!empty($conditions)) {
            $i = 0;
            foreach ($conditions as $key => $value) {
                if ($i === 0) {
                    $sql = $sql . " WHERE $key=:$key";
                } else {
                    $sql = $sql . " $conjunction $key=:$key";
                }
                $i++;
            }
        }

        if (!empty($order_by)) {
            $sql .= " ORDER BY " . $order_by[0] . " $order_by[1]";
        }

        $stmt = self::executeQuery($sql, $conditions);
        $records = $stmt->fetchAll();
        return $records;
    }

    public static function selectOne($conditions, $conjunction = "AND") {
        $sql = "SELECT * FROM " . static::$tableName;

        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=:$key";
            } else {
                $sql = $sql . " $conjunction $key=:$key";
            }
            $i++;
        }

        $sql = $sql . " LIMIT 1";
        $stmt = self::executeQuery($sql, $conditions);
        $records = $stmt->fetch();
        return $records;
    }

    public static function create($rawData) {
        global $conn;
        $sql = "INSERT INTO " . static::$tableName;
        $data = self::getFillableData($rawData);

        if (isset(static::$timestamps) && static::$timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " SET $key=:$key";
            } else {
                $sql = $sql . ", $key=:$key";
            }
            $i++;
        }

        $stmt = self::executeQuery($sql, $data);
        $id = $conn->lastInsertId();
        return $id;
    }

    public static function update($id, $rawData, $updateTimestamps = true) {
        $sql = "UPDATE " . static::$tableName;
        $data = self::getFillableData($rawData);

        if (isset(static::$timestamps) && static::$timestamps && $updateTimestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " SET $key=:$key";
            } else {
                $sql = $sql . ", $key=:$key";
            }
            $i++;
        }

        $sql = $sql . " WHERE id=:id";
        $data['id'] = $id;
        $stmt = self::executeQuery($sql, $data);
        return $data['id'];
    }

    public static function delete($conditions, $conjunction = "OR") {
        $sql = "DELETE FROM " . static::$tableName;

        $i = 0;
        foreach ($conditions as $key => $value) {
            if ($i === 0) {
                $sql = $sql . " WHERE $key=:$key";
            } else {
                $sql = $sql . " $conjunction $key=:$key";
            }
            $i++;
        }

        $stmt = self::executeQuery($sql, $conditions);
        return $stmt->rowCount();
    }
}