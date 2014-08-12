/* Smart card signing utility */

// CAPICOM constants
var CAPICOM_STORE_OPEN_READ_ONLY = 0;
var CAPICOM_CURRENT_USER_STORE = 2;
var CAPICOM_CERTIFICATE_FIND_SHA1_HASH = 0;
var CAPICOM_CERTIFICATE_FIND_EXTENDED_PROPERTY = 6;
var CAPICOM_CERTIFICATE_FIND_TIME_VALID = 9;
var CAPICOM_CERTIFICATE_FIND_KEY_USAGE = 12;
var CAPICOM_DIGITAL_SIGNATURE_KEY_USAGE = 0x00000080;
var CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME = 0;
var CAPICOM_INFO_SUBJECT_SIMPLE_NAME = 0;
var CAPICOM_ENCODE_BASE64 = 0;
var CAPICOM_E_CANCELLED = -2138568446;
var CERT_KEY_SPEC_PROP_ID = 6;

function isUserVerified(admin)
{
    if (admin != getUser())
    {
        alert("You do not have sufficient permission to complete this action");
        return false;
    }

    return true;
}

function getUser() 
{
    /*if (window.crypto && window.crypto.signText)
    {
        return sign_NS(src);
	}*/
	if (isIE()) 
    {
		return getUser_IE();
	}
	
	alert("Sorry, your browser does not support smart cards authentication");
	return "";
}

function sign_NS(src) 
{
    return crypto.signText(src, "ask");
}

function isIE() 
{
	if ("ActiveXObject" in window)
    {
		return true;
	}
	return false;
}

function findCertificateByHash() 
{
    try 
    {
        // instantiate the CAPICOM objects
        var store = new ActiveXObject("CAPICOM.Store");
        // open the current users personal certificate store
        store.Open(CAPICOM_CURRENT_USER_STORE, "My", CAPICOM_STORE_OPEN_READ_ONLY);
        // Show personal certificates which are installed for this user
        var certificates = store.Certificates.Select(
            "Smart Card Authentication", "Please select a certificate to authenticate.");		
        var signer = new ActiveXObject("CAPICOM.Signer");
        signer.Certificate = certificates.Item(1);
        return signer;

    } 
    catch (e) 
    {
        if (e.number != CAPICOM_E_CANCELLED)
            return new ActiveXObject("CAPICOM.Signer");
    }
}

/* Sign method for internet explorer support */
function getUser_IE() 
{
    try
    {
        // instantiate the CAPICOM objects
        var signedData = new ActiveXObject("CAPICOM.SignedData");
        var timeAttribute = new ActiveXObject("CAPICOM.Attribute");

        // Set the data that we want to sign
        signedData.Content = "test";
        var signer = findCertificateByHash();

        // Set the time in which we are applying the signature
        var today = new Date();
        timeAttribute.Name = CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME;
        timeAttribute.Value = today.getVarDate();
        signer.AuthenticatedAttributes.Add(timeAttribute);

        // Do the Sign operation
        var signed = signedData.Sign(signer, true, CAPICOM_ENCODE_BASE64);
		// Important: IE uses UTF-16LE to encode the signed data

        var subjectName = signer.Certificate.subjectName;
        var part = "SERIALNUMBER=";
        var start = subjectName.indexOf(part);
        var end = subjectName.indexOf(",", start);
        var userName = subjectName.substring(start + part.length, end); 
        return userName;
    }
    catch (e)
    {
        if (e.number != CAPICOM_E_CANCELLED)
        {
            alert("An error occurred when attempting to sign the content, the error was: " + e.description);
        }
    }

    return "";
}
