1.) Daten aus dem Ynfinite lesen. GET v1/content_type/p/{id}/content
2.) Filtermasken generieren aus ContentType GET v1/content_type/{id} => fields
Content Typen laden, Felder auswählen (siehe Masken)

Filter Object:

{
	filter: {
			"name": {
				operation: "=", // "=", "!=", "<=", ">=", "range"
				value: "FromValue"
			},
			"date": {
				operation: "range", // "=", "!=", "<=", ">=", "range"
				value: "FromValue"
				value2: "ToValue"
			},
		}
	],
	sort: {
		field: "Name", 
		direction: "DESC" // ASC, DESC
	},
	skip: 0,
	limit: 10
}

3.) Inhalte müssen gecached werden.

4.) Liste von Inhalten (mit Filterkriterien)
5.) Einzelne Inhalte mit ID aus Ynfinite (ID entweder fest im Inhaltselement hinterlegt oder als Parameter von aussen)