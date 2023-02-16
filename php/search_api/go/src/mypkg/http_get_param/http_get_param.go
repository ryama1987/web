package http_get_param

import (
    "fmt"
    "mymodule/mypkg/database"
    "net/http"
    "strings"
    "golang.org/x/text/width"
)

// GetKeyword ハンドラ
func GetKeyword(w http.ResponseWriter, r *http.Request) {
  //getパラメータのキーワードを取得
  keyword := r.FormValue("key")

  //漢字・ひらがな・カタカナを全角に、英数字を半角に統一
  keyword_cnv := width.Fold.String(keyword)

  //半角空白でキーワード分割
  split_word := strings.Split(keyword_cnv, " ")
  fmt.Println(split_word[0])
  
  //for _, ra:=range split_word{
  //  fmt.Println("keyは", ra)
  //}

  if len(split_word) >= 2  {
		result := database.MySqlConnectWithArrWord(split_word)
    fmt.Fprint(w,string(result))
	} else {
		result := database.MySqlConnect(split_word[0])
    fmt.Fprint(w,string(result))
	}
}