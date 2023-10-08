<?php
namespace core\components\blog;
require_once 'vendor/autoload.php';

use core\image\Image;

class Blog
{
    private \PDO|null $conn = null;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public static function getList($conn, $sortType = 'DESC'): array
    {
        $stm = $conn->query("SELECT * FROM blog ORDER BY date $sortType");

        $arResult = $stm->fetchAll(\PDO::FETCH_ASSOC);

        return $arResult;
    }

    public function insertBlogElement($title, $alt, $text, Image $image): void
    {
        $full_path = $image->getFullPath();
        $img_path = substr("$full_path", strpos("$full_path", '/uploads'));

        $sth = $this->conn->prepare("insert into blog(title, img, img_alt, text) values (:title, :img_path, :alt, :text)");
        $sth->execute([
            'title' => $title,
            'img_path' => $img_path,
            'alt' => $alt,
            'text' => $text
        ]);
    }

    public function deleteBlogElementById($id): void
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . self::getImagePath($id)))
        {
            Image::deleteImage(self::getImagePath($id));
        }
        $sth = $this->conn->prepare("DELETE FROM blog WHERE id = :id");
        $sth->execute(['id' => $id]);
    }

    public function getImagePath($id)
    {
        $res = self::getBlogElementById($id, 'img');
        return $res['img'];
    }

    public function getBlogElementById($id, $field = '*'): mixed
    {
        $stm = $this->conn->query("SELECT $field FROM blog WHERE id = $id");
        $res = $stm->fetch(\PDO::FETCH_ASSOC);
        return $res;
    }
}