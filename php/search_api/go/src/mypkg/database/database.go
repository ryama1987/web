package database

import (
	"database/sql"
	"fmt"
	"log"
	"os"
	"strings"
	"encoding/json"
	"github.com/joho/godotenv"
	"github.com/jmoiron/sqlx"
	_ "github.com/go-sql-driver/mysql"
)

type Searchapi struct {
	// **note** 構造体の指定は、大文字からはじめないとエラーが起こる
	Id string `db:"Id" json:"Id" `
	Name string `db:"Name" json:"Name"`
	Catch__c string `db:"Catch__c" json:"Catch__c"`
	Prefecture1__c sql.NullString `db:"Prefecture1__c" json:"Prefecture1__c"`
	City1__c sql.NullString `db:"City1__c" json:"City1__c"`
}

//type Searchapi struct {
	// **note** 構造体の指定は、大文字からはじめないとエラーが起こる
//	Id int `db:"id" json:"id" `
//	Name string `db:"name" json:"name"`
//	CityName string `db:"city_name" json:"city_name"`
//}

/*func GetRows(db *sqlx.DB) {
	rows, err := db.Query("SELECT id,name,city_name FROM corporate_nums WHERE MATCH (city_name) AGAINST ('千代田' IN NATURAL LANGUAGE MODE) AND MATCH (name) AGAINST ('図書館' IN NATURAL LANGUAGE MODE);")
	if err != nil {
		log.Fatalf("getRows db.Query error err:%v", err)
	}
	defer rows.Close()

	for rows.Next() {
		data := &SqlData{}
		if err := rows.Scan(&data.id, &data.name, &data.city_name); err != nil {
			log.Fatalf("getRows rows.Scan error err:%v", err)
		}
		fmt.Println(data)
	}

	err = rows.Err()
	if err != nil {
		log.Fatalf("getRows rows.Err error err:%v", err)
	}
}*/

//キーワードを分割しない場合（1つのみ）
func GetRows_test(db *sqlx.DB, word string) []byte {
	//query  := "SELECT id,name,city_name FROM corporate_nums WHERE MATCH (name) AGAINST ($1 IN NATURAL LANGUAGE MODE)"

	//str_test  := "SELECT id,name FROM cards"
	//query := fmt.Sprintf("%s", str_test)
	//fmt.Println("---------query---------")
	//fmt.Println(query)

	//rows, err := db.Queryx("SELECT id,name,city_name FROM corporate_nums WHERE MATCH (name) AGAINST (? IN NATURAL LANGUAGE MODE) OR MATCH (city_name) AGAINST (? IN NATURAL LANGUAGE MODE)", word, word)

	rows, err := db.Queryx("SELECT Id,Name,Catch__c,Prefecture1__c,City1__c FROM api_jobinfo WHERE MATCH (Catch__c,BusinessContents__c, Qualification__c, ApplyRequirement__c, Requirement__c, Prefecture1__c, City1__c) AGAINST (? IN BOOLEAN MODE)", word)

	if err != nil {
		log.Fatalf("getRows db.Queryx error err:%v", err)
	}
	defer rows.Close()

	datum := make([]Searchapi,0)
	for rows.Next() {
		var data Searchapi
		err := rows.StructScan(&data);
		if err != nil {
			log.Fatalf("getRows rows.StructScan error err:%v", err)
		}
		datum = append(datum, data)
	}

	err = rows.Err()
	if err != nil {
		log.Fatalf("getRows rows.Err error err:%v", err)
	}

	//JSONに変換
	json, err := json.Marshal(datum)

	if err != nil {
		log.Fatalf("json.Marshal error err:%v", err)
	}

	//fmt.Println("%v\n",string(json))
	return json
}

//キーワードを分割する場合（2つ以上）
func GetRows_test2(db *sqlx.DB, word []string) []byte {
	
	//部分一致のword作成
	word[0] = "+" + word[0]

	//複数の単語を「 +」でつなげる
	comb_word := strings.Join(word, " +")

	fmt.Println(comb_word)
	
	//DBにクエリ―を投げる
	rows, err := db.Queryx("SELECT Id,Name,Catch__c,Prefecture1__c,City1__c FROM api_jobinfo WHERE MATCH (Catch__c,BusinessContents__c, Qualification__c, ApplyRequirement__c, Requirement__c, Prefecture1__c, City1__c) AGAINST (? IN BOOLEAN MODE)", comb_word)

	if err != nil {
		log.Fatalf("getRows db.Queryx error err:%v", err)
	}
	defer rows.Close()

	datum := make([]Searchapi,0)
	for rows.Next() {
		var data Searchapi
		err := rows.StructScan(&data);
		if err != nil {
			log.Fatalf("getRows rows.StructScan error err:%v", err)
		}
		datum = append(datum, data)
	}

	err = rows.Err()
	if err != nil {
		log.Fatalf("getRows rows.Err error err:%v", err)
	}

	//JSONに変換
	json, err := json.Marshal(datum)

	if err != nil {
		log.Fatalf("json.Marshal error err:%v", err)
	}

	//fmt.Println("%v\n",string(json))
	return json
}

func ConnectSetting() *sqlx.DB {
    err := godotenv.Load()
    if err != nil {
        fmt.Println(err.Error())
    }

	user := os.Getenv("DB_USER")
    password := os.Getenv("DB_PASSWORD")
    host := os.Getenv("DB_HOST")
    port := os.Getenv("DB_PORT")
    database_name := os.Getenv("DB_DATABASE_NAME")

    dbconf := user + ":" + password + "@tcp(" + host + ":" + port + ")/" + database_name + "?charset=utf8mb4"
    db_setting, err := sqlx.Open("mysql", dbconf)
    if err != nil {
        fmt.Println(err.Error())
    }
    return db_setting
}

func MySqlConnect(word string) []byte {
	db := ConnectSetting()
	defer db.Close()

	// 実際に接続する
	err := db.Ping()

	if err != nil {
		fmt.Println("データベース接続失敗")
	} else {
		fmt.Println("データベース接続成功")
	}

	fmt.Println("------------------")
	res := GetRows_test(db,word)
	return res
}

func MySqlConnectWithArrWord(word []string) []byte {
	db := ConnectSetting()
	defer db.Close()

	// 実際に接続する
	err := db.Ping()

	if err != nil {
		fmt.Println("データベース接続失敗")
	} else {
		fmt.Println("データベース接続成功")
	}

	fmt.Println("------------------")
	res := GetRows_test2(db,word)
	return res
}
