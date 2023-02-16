package main

import (
	"mymodule/mypkg/http_get_param"
	"net/http"
	"github.com/gorilla/mux"
)

func main() {
	r := mux.NewRouter()
	r.HandleFunc("/search_api_get", http_get_param.GetKeyword)
	
	http.Handle("/",r)
	http.ListenAndServe(":90", nil)
}


