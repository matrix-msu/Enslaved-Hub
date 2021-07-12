var options = {locale: 'en'};

jOWL.load(BASE_JOWL_URL + "data/EnslavedOWLOntology.owl", function(){
    var tree = $('.ontology-wrapper').owl_treeview({
        addChildren: true,
        isStatic: true,
        rootThing: true,
    });

    var cc = jOWL.permalink() || jOWL("owl:Thing");
    tree.propertyChange(cc);
    tree.broadcast(cc);

}, {} );