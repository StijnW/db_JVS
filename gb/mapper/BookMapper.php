<?php
namespace gb\mapper;

$EG_DISABLE_INCLUDES=true;
require_once( "gb/mapper/Mapper.php" );
require_once( "gb/domain/Book.php" );


class BookMapper extends Mapper {

    function __construct() {
        parent::__construct();
        $this->selectStmt = "SELECT b.* FROM book b where b.uri = ?";
        $this->selectAllStmt = "SELECT * FROM book";        
    } 
    
    function getCollection( array $raw ) {
        
        $bookCollection = array();
        foreach($raw as $row) {
            print_r($row);
            array_push($bookCollection, $this->doCreateObject($row));
        }
        
        return $bookCollection;
    }

    protected function doCreateObject( array $array ) {
        
        $obj = null;        
        if (count($array) > 0) {
            $obj = new \gb\domain\Book( $array['name'] );
            $obj->setBookName($array["name"]);
            $obj->setDescription($array['description']);            
        } 
        
        return $obj;
    }

    protected function doInsert( \gb\domain\DomainObject $object ) {
        /*$values = array( $object->getName() ); 
        $this->insertStmt->execute( $values );
        $id = self::$PDO->lastInsertId();
        $object->setId( $id );*/
    }
    
    function update( \gb\domain\DomainObject $object ) {
        //$values = array( $object->getName(), $object->getId(), $object->getId() ); 
        //$this->updateStmt->execute( $values );
    }

    function selectStmt() {
        return $this->selectStmt;
    }
    
    function selectAllStmt() {
        return $this->selectAllStmt;
    }
    
    function getBookByGenre($genre){
        $con = $this->getConnectionManager();
        $selectStmt = "SELECT b.name, b.description, a.name
                        FROM (award a JOIN wins_award w ON a.uri = w.book_uri)
                             LEFT OUTER JOIN
                             (book b JOIN has_genre h ON b.uri = h.book_uri)
                             ON w.genre_uri = h.genre_uri
                        WHERE h.genre_uri = '$genre'";
        print "Werkt nog niet deftig!! print statement uitzetten op line 21!"
        $writers = $con->executeSelectStatement($selectStmt, array()); 
        return $this->getCollection($writers);
    }
    
}


?>
