package main

import (
	"mymodule/mypkg/http_get_param"
	"net/http"
)

func main() {
		http.HandleFunc("/search_api_get", http_get_param.GetKeyword)
		http.ListenAndServe(":90", nil)
}


