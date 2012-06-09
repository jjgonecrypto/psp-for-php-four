<?php

/** A simple encapsulation of the DOMXML package in php4 to 
 * emulate the PHP5 DOM package, and help create a smooth transition 
 * between versions.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PspDomNode
{
	var $DomNode = null;
	
	function PspDomNode($node)
	{
		$this->DomNode = $node;	
	}	
	
	/** Static function to create a new encapsulating class for the 
	 * DOM object.
	 * @param DomNode
	 * @return PspDomNode
	 */ 
	function NodeFactory(&$node)
	{
		if (is_a($node,"DomElement"))
		{
			return new PspDomElement($node);
		}
		else if (is_a($node,"DomDocument"))
		{
			$doc = new PspDomDocument();
			$doc->SetDocument($node);
			return $doc;
		}
		else
		{
			return new PspDomNode($node);	
		}	
	}
	
	
	/** Inserts the node list before this node in the DOM
	 * @param Array The list of nodes
	 */
	function InsertBeforeMe($node_array)
	{
		$parent = $this->ParentNode();	
		
		foreach ($node_array as $n)
		{
			$parent->InsertBefore($n,$this);	
		}
	}
	
	
	function IsTextNode()
	{
		return is_a($this->DomNode,"DomText");	
	}
	
	function IsElement()
	{
		return is_a($this->DomNode,"DomElement");	
	}
	
	// {{{ DOM Regions
	
	/**
	 * @param PspDomNode
	 * @return PspDomNode
	 */
	function AppendChild($newnode)
	{
		$newappend = $this->DomNode->append_child($newnode->DomNode);
		
		return $this->NodeFactory($newappend);
	}
	
	
	
	/**
	 * @param bool Deep clone
	 * @return PspDomNode
	 */
	function CloneNode($deep)
	{
		return $this->NodeFactory($this->DomNode->clone_node(true));	
	}
	
	/**
	 * @return array [int => PspDomNode]
	 */
	function ChildNodes()
	{
		$tmp = array();
		
		foreach ($this->DomNode->child_nodes() as $child)
		{
			$tmp[] = $this->NodeFactory($child);	
		}	
		
		return $tmp;
	}
	
	function HasChildNodes()
	{
		return $this->DomNode->has_child_nodes();	
	}
	
	/** Does an Import node and an AppendChild in PHP4.
	 * @param PspDomNode
	 */
	function AppendExternal($newnode)
	{
		if (!$this->HasChildNodes())
		{
			//NOTE: workaround for PHP4 without import node
			$this->AppendEmptyNode();
		}
		
		$firstChild = $this->FirstChild();
		
		$newAppend = $firstChild->DomNode->append_sibling($newnode->DomNode);	
		
		return $this->NodeFactory($newAppend);
	}
	
	function AppendEmptyNode()
	{
		$doc = $this->OwnerDocument();
		
		$this->AppendChild($doc->CreateTextNode(""));
	}
	
	/** Inserts the node as this node's first child. This also acts 
	 * as ImportNode() for PHP4.
	 * @param PspDomNode
	 */
	function InsertFirst($newnode)
	{
		if (!$this->HasChildNodes())
		{
			//NOTE: workaround for PHP4 without import node
			$this->AppendEmptyNode();
		}
		
		return $this->InsertBefore($newnode, $this->FirstChild());
	}
	/**
	 * @param bool Deep clone
	 * @return PspDomNode
	 */
	function InsertBefore($newnode, $refnode)
	{
		$newbefore = $this->DomNode->insert_before($newnode->DomNode, $refnode->DomNode);
		
		return $this->NodeFactory($newbefore);
	}
	
	
	function ParentNode()
	{
		$parent = $this->DomNode->parent_node();
		
		return $this->NodeFactory($parent);	
	}
	
	function RemoveChild($child)
	{
		//if ($this->DomNode == null) var_dump($child->GetAttributes());
		return $this->NodeFactory($this->DomNode->remove_child($child->DomNode));	
	}
	
	function ReplaceChild($new, $old)
	{
		return $this->NodeFactory($this->DomNode->replace_child($new->DomNode, $old->DomNode));
	}
	
	function GetNodeName()
	{
		return $this->DomNode->node_name();
	}
	
	function GetNodeValue()
	{
		return $this->DomNode->node_value();
	}
	
	function SetNodeValue($value)
	{
		//NOTE: php5 -> should be $this->DomNode->nodeValue = $val
		$this->DomNode->set_content($value);
	}
	
	function GetNodeContent()
	{
		//PHP5 is $this->DomNode->textContent;
		return $this->DomNode->get_content();	
	}
	
	//NOTE: will encode anyway! 
	function SetNodeContent($content, $encode = true)
	{
		//PHP5 is $this->DomNode->textContent = $content;
		if ($encode)
		{
			return $this->DomNode->set_content(htmlentities($content));	
		}
		else
		{
			return $this->DomNode->set_content($content);
		}
	}
	
	
	function OwnerDocument()
	{
		$doc = new PspDomDocument();
		$doc->SetDocument($this->DomNode->owner_document());
		return $doc;
	}
	
	function InnerXml()
	{
		$doc = $this->DomNode->owner_document();
		
		//remove leading and closing tags
		return preg_replace(array("/^<.+?>/","/<\/.+?>$/"),array("",""),$doc->dump_node($this->DomNode));
				
	}
	
	function GetAttributes()
	{
		$tmp = array();
		
		$atts = $this->DomNode->attributes();	
		
		if (is_array($atts))
		{
			foreach ($atts as $a)
			{
				$tmp[] = new PspDomAttr($a);	
			}
		}
		
		return $tmp;
	}
	
	function HasAttributes()
	{
		return $this->DomNode->has_attributes();	
	}
	
	function FirstChild()
	{
		//reqs PHP >= 4.3.0
		return $this->NodeFactory($this->DomNode->first_child());	
	}

	
	// }}}
	
}
?>