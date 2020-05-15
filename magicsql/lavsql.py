import json
#Her importerer jeg Pythons json-modul

with open('scryfall-default-cards.json', 'r', encoding="utf-8") as f:
	card_List = json.load(f)
    #Her åbnes json-filen med kort, det gemmes i variablen card_List
	f.close()

writefile = open('kortting.txt', 'w', encoding="utf-8")
tal = 1
#Der åbnes en ny txt-fil der kan skrives i, og hjælpevariablen tal oprettes
for e in card_List:
    if e['object'] == "card":
        #For hvert element i card_List, tjekket først om det er et kort-objekt
        name = str(e['name'])
        expset = str(e['set_name'])
        #Hvis det er gemmes kortets navn og expansion set
        writefile.write("(" + str(tal) + ", '" + str(name.replace("'", "")) + "', '" + str(expset.replace("'", "")) + "'),\n")
        #Der skrives i filen på den form det kan bruges ien sql-fil til at insætte data
        tal += 1

