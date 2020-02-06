Feature: Events Hero


  @api @cit @javascript @events
   # L’EVENT promu à venir s'affiche dans le Hero de la listing EVENT.
  Scenario: Bloc Hero for the events page.
    When I visit "/events"
    #Then I should see an "body.taxonomy-family" element
    Then take a screenshot

  #@api @cit @javascript @event_hero
  #Si 0 EVENT épinglé, le prochain Event est mis en avant. (Hypothèse = date de début+ proche de la date du jour)
  #Scenario:

  #Si plusieurs Events sont promus , on affiche le plus proche (Hypothèse = date de début+ proche de la date du jour)
  #@api @cit @javascript @event_hero
  #Scenario:

    #Lorsque l'EVENT est passé, ce dernier disparaît du Highlight.
 # @api @cit @javascript @event_hero
   # Scenario:
