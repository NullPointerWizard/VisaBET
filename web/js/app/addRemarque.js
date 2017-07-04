var $collectionHolder;

// setup an "add a tag" link
var $addRemLink = $('<a href="#" class="add_tag_link"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> Remarque </a>');
var $newLinkLi = $('<li class="list-group-item"></li>').append($addRemLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.remarques');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    //On rajoute fonction pour enlever les remarques
    $collectionHolder.find('li:not(:last)').each(function() {
        addRemFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addRemLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addRemForm($collectionHolder, $newLinkLi);
    });
});


//Remarque Form
function addRemForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="list-group-item"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addRemFormDeleteLink($newFormLi);
}

function addRemFormDeleteLink($remFormLi) {
    var $removeFormA = $('<a href="#"><i class="fa fa-minus-square fa-lg" aria-hidden="true"></i></a>');
    $remFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the remarque form
        $remFormLi.remove();
    });
}
