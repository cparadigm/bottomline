
var Sortable = {
    SERIALIZE_RULE: /^[^_\-](?:[A-Za-z0-9\-\_]*)[_](.*)$/,
    sortables: {},
    _findRootElement: function(element) {
	while (element.tagName.toUpperCase() != "BODY") {
	    if (element.id && Sortable.sortables[element.id])
		return element;
	    element = element.parentNode;
	}
    },
    options: function(element) {
	element = Sortable._findRootElement($(element));
	if (!element)
	    return;
	return Sortable.sortables[element.id];
    },
    destroy: function(element) {
	element = $(element);
	var s = Sortable.sortables[element.id];
	if (s) {
	    Draggables.removeObserver(s.element);
	    s.droppables.each(function(d) {
		Droppables.remove(d)
	    });
	    s.draggables.invoke('destroy');
	    delete Sortable.sortables[s.element.id];
	}
    },
    create: function(element) {
	element = $(element);
	var options = Object.extend({
	    element: element,
	    tag: 'li', // assumes li children, override with tag: 'tagname'
	    dropOnEmpty: false,
	    tree: false,
	    treeTag: 'ul',
	    overlap: 'vertical', // one of 'vertical', 'horizontal'
	    constraint: 'vertical', // one of 'vertical', 'horizontal', false
	    containment: element, // also takes array of elements (or id's); or false
	    handle: false, // or a CSS class
	    only: false,
	    delay: 0,
	    hoverclass: null,
	    ghosting: false,
	    quiet: false,
	    scroll: false,
	    scrollSensitivity: 20,
	    scrollSpeed: 15,
	    format: this.SERIALIZE_RULE,
	    // these take arrays of elements or ids and can be
	    // used for better initialization performance
	    elements: false,
	    handles: false,
	    onChange: Prototype.emptyFunction,
	    onUpdate: Prototype.emptyFunction
	}, arguments[1] || {});
	// clear any old sortable with same element
	this.destroy(element);
	// build options for the draggables
	var options_for_draggable = {
	    revert: true,
	    quiet: options.quiet,
	    scroll: options.scroll,
	    scrollSpeed: options.scrollSpeed,
	    scrollSensitivity: options.scrollSensitivity,
	    delay: options.delay,
	    ghosting: options.ghosting,
	    constraint: options.constraint,
	    handle: options.handle};
	if (options.starteffect)
	    options_for_draggable.starteffect = options.starteffect;
	if (options.onStart)
	    options_for_draggable.onStart = options.onStart;
	if (options.onDrag)
	    options_for_draggable.onDrag = options.onDrag;
	if (options.change)
	    options_for_draggable.change = options.change;
	if (options.onEnd)
	    options_for_draggable.onEnd = options.onEnd;
	if (options.reverteffect)
	    options_for_draggable.reverteffect = options.reverteffect;
	else
	if (options.ghosting)
	    options_for_draggable.reverteffect = function(element) {
		element.style.top = 0;
		element.style.left = 0;
	    };
	if (options.endeffect)
	    options_for_draggable.endeffect = options.endeffect;
	if (options.zindex)
	    options_for_draggable.zindex = options.zindex;
	// build options for the droppables
	var options_for_droppable = {
	    overlap: options.overlap,
	    containment: options.containment,
	    tree: options.tree,
	    hoverclass: options.hoverclass,
	    onHover: Sortable.onHover
	};
	var options_for_tree = {
	    onHover: Sortable.onEmptyHover,
	    overlap: options.overlap,
	    containment: options.containment,
	    hoverclass: options.hoverclass
	};
	// fix for gecko engine
	Element.cleanWhitespace(element);
	options.draggables = [];
	options.droppables = [];
	// drop on empty handling
	if (options.dropOnEmpty || options.tree) {
	    Droppables.add(element, options_for_tree);
	    options.droppables.push(element);
	}

	(options.elements || this.findElements(element, options) || []).each(function(e, i) {
	    var handle = options.handles ? $(options.handles[i]) :
		    (options.handle ? $(e).select('.' + options.handle)[0] : e);
	    options.draggables.push(
		    new Draggable(e, Object.extend(options_for_draggable, {handle: handle})));
	    Droppables.add(e, options_for_droppable);
	    if (options.tree)
		e.treeNode = element;
	    options.droppables.push(e);
	});
	if (options.tree) {
	    (Sortable.findTreeElements(element, options) || []).each(function(e) {
		Droppables.add(e, options_for_tree);
		e.treeNode = element;
		options.droppables.push(e);
	    });
	}

	// keep reference
	this.sortables[element.identify()] = options;
	// for onupdate
	Draggables.addObserver(new SortableObserver(element, options.onUpdate));
    },
    // return all suitable-for-sortable elements in a guaranteed order
    findElements: function(element, options) {
	return Element.findChildren(
		element, options.only, options.tree ? true : false, options.tag);
    },
    findTreeElements: function(element, options) {
	return Element.findChildren(
		element, options.only, options.tree ? true : false, options.treeTag);
    },
    onHover: function(element, dropon, overlap) {
	if (Element.isParent(dropon, element))
	    return;
	if (overlap > .33 && overlap < .66 && Sortable.options(dropon).tree) {
	    return;
	} else if (overlap > 0.5) {
	    Sortable.mark(dropon, 'before');
	    if (dropon.previousSibling != element) {
		var oldParentNode = element.parentNode;
		element.style.visibility = "hidden"; // fix gecko rendering
		dropon.parentNode.insertBefore(element, dropon);
		if (dropon.parentNode != oldParentNode)
		    Sortable.options(oldParentNode).onChange(element);
		Sortable.options(dropon.parentNode).onChange(element);
	    }
	} else {
	    Sortable.mark(dropon, 'after');
	    var nextElement = dropon.nextSibling || null;
	    if (nextElement != element) {
		var oldParentNode = element.parentNode;
		element.style.visibility = "hidden"; // fix gecko rendering
		dropon.parentNode.insertBefore(element, nextElement);
		if (dropon.parentNode != oldParentNode)
		    Sortable.options(oldParentNode).onChange(element);
		Sortable.options(dropon.parentNode).onChange(element);
	    }
	}
    },
    onEmptyHover: function(element, dropon, overlap) {
	var oldParentNode = element.parentNode;
	var droponOptions = Sortable.options(dropon);
	if (!Element.isParent(dropon, element)) {
	    var index;
	    var children = Sortable.findElements(dropon, {tag: droponOptions.tag, only: droponOptions.only});
	    var child = null;
	    if (children) {
		var offset = Element.offsetSize(dropon, droponOptions.overlap) * (1.0 - overlap);
		for (index = 0; index < children.length; index += 1) {
		    if (offset - Element.offsetSize(children[index], droponOptions.overlap) >= 0) {
			offset -= Element.offsetSize(children[index], droponOptions.overlap);
		    } else if (offset - (Element.offsetSize(children[index], droponOptions.overlap) / 2) >= 0) {
			child = index + 1 < children.length ? children[index + 1] : null;
			break;
		    } else {
			child = children[index];
			break;
		    }
		}
	    }

	    dropon.insertBefore(element, child);
	    Sortable.options(oldParentNode).onChange(element);
	    droponOptions.onChange(element);
	}
    },
    unmark: function() {
	if (Sortable._marker)
	    Sortable._marker.hide();
    },
    mark: function(dropon, position) {
	// mark on ghosting only
	var sortable = Sortable.options(dropon.parentNode);
	if (sortable && !sortable.ghosting)
	    return;
	if (!Sortable._marker) {
	    Sortable._marker =
		    ($('dropmarker') || Element.extend(document.createElement('DIV'))).
		    hide().addClassName('dropmarker').setStyle({position: 'absolute'});
	    document.getElementsByTagName("body").item(0).appendChild(Sortable._marker);
	}
	var offsets = dropon.cumulativeOffset();
	Sortable._marker.setStyle({left: offsets[0] + 'px', top: offsets[1] + 'px'});
	if (position == 'after')
	    if (sortable.overlap == 'horizontal')
		Sortable._marker.setStyle({left: (offsets[0] + dropon.clientWidth) + 'px'});
	    else
		Sortable._marker.setStyle({top: (offsets[1] + dropon.clientHeight) + 'px'});
	Sortable._marker.show();
    },
    _tree: function(element, options, parent) {
	var children = Sortable.findElements(element, options) || [];
	for (var i = 0; i < children.length; ++i) {
	    var match = children[i].id.match(options.format);
	    if (!match)
		continue;
	    var child = {
		id: encodeURIComponent(match ? match[1] : null),
		element: element,
		parent: parent,
		children: [],
		position: parent.children.length,
		container: $(children[i]).down(options.treeTag)
	    };
	    /* Get the element containing the children and recurse over it */
	    if (child.container)
		this._tree(child.container, options, child);
	    parent.children.push(child);
	}

	return parent;
    },
    tree: function(element) {
	element = $(element);
	var sortableOptions = this.options(element);
	var options = Object.extend({
	    tag: sortableOptions.tag,
	    treeTag: sortableOptions.treeTag,
	    only: sortableOptions.only,
	    name: element.id,
	    format: sortableOptions.format
	}, arguments[1] || {});
	var root = {
	    id: null,
	    parent: null,
	    children: [],
	    container: element,
	    position: 0
	};
	return Sortable._tree(element, options, root);
    },
    /* Construct a [i] index for a particular node */
    _constructIndex: function(node) {
	var index = '';
	do {
	    if (node.id)
		index = '[' + node.position + ']' + index;
	} while ((node = node.parent) != null);
	return index;
    },
    sequence: function(element) {
	element = $(element);
	var options = Object.extend(this.options(element), arguments[1] || {});
	return $(this.findElements(element, options) || []).map(function(item) {
	    return item.id.match(options.format) ? item.id.match(options.format)[1] : '';
	});
    },
    setSequence: function(element, new_sequence) {
	element = $(element);
	var options = Object.extend(this.options(element), arguments[2] || {});
	var nodeMap = {};
	this.findElements(element, options).each(function(n) {
	    if (n.id.match(options.format))
		nodeMap[n.id.match(options.format)[1]] = [n, n.parentNode];
	    n.parentNode.removeChild(n);
	});
	new_sequence.each(function(ident) {
	    var n = nodeMap[ident];
	    if (n) {
		n[1].appendChild(n[0]);
		delete nodeMap[ident];
	    }
	});
    },
    serialize: function(element) {
	element = $(element);
	var options = Object.extend(Sortable.options(element), arguments[1] || {});
	var name = encodeURIComponent(
		(arguments[1] && arguments[1].name) ? arguments[1].name : element.id);
	if (options.tree) {
	    return Sortable.tree(element, arguments[1]).children.map(function(item) {
		return [name + Sortable._constructIndex(item) + "[id]=" +
			    encodeURIComponent(item.id)].concat(item.children.map(arguments.callee));
	    }).flatten().join('&');
	} else {
	    return Sortable.sequence(element, arguments[1]).map(function(item) {
		return name + "[]=" + encodeURIComponent(item);
	    }).join('&');
	}
    }
};
varienGrid = Class.create(varienGrid, {
    initGrid: function($super) {
	$super(); // Calling parent method functionality
	var table = $(this.containerId + this.tableSufix);
	this.sortedContainer = table.down('tbody');
	Sortable.create(this.sortedContainer.identify(), {
	    tag: 'TR',
	    dropOnEmpty: true,
	    containment: [this.sortedContainer.identify()],
	    constraint: false,
	    onEnd: this.updateSort.bind(this),
	    ghosting: false,
	    onStart: this.dragStart.bind(this),
	    onDrag: this.dragDrag.bind(this),
	    change: this.dragChange.bind(this),
	    snap: [40, 40]
	});
    },
    updateSort: function(e)
    {
	e.element.removeClassName("dragging");
	var rows = this.sortedContainer.childElements(); // Getting all rows
	console.log($$('.sort-arrow-desc')[0]);
	if ($$('.sort-arrow-desc')[0]) {
	    for (var i = rows.length - 1, l = 0; i > l - 1; i--) {
		if (rows[i].down('input[name="position"]')) {
		    rows[i].down('input[name="position"]').value = i;
		    rows[i].down('input[name="position"]').setHasChanges({});
		}
	    }
	}
	else {
	    for (var i = 0, l = rows.length; i < l; i++) {
		if (rows[i].down('input[name="position"]')) {
		    rows[i].down('input[name="position"]').value = i;
		    rows[i].down('input[name="position"]').setHasChanges({});
		}
	    }

	}
    },
    dragStart: function(e)
    {
	e.element.addClassName("dragging");
    },
    dragDrag: function(e)
    {
	e.element.addClassName("dragging");
    },
    dragChange: function(e)
    {
	e.element.addClassName("dragging");
    }


});

