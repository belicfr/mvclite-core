<?php

namespace MvcliteCore\Engine\Security;

use MvcliteCore\Engine\Session\Session;
use MvcliteCore\Router\Exceptions\UndefinedInputException;
use MvcliteCore\Router\Request;

/**
 * Validation class.
 *
 * This class allows developer to validate POST
 * inputs following established and punctual rules.
 *
 * @author belicfr
 */
class Validator
{
    /** Current request object. */
    private Request $request;

    /** Current rules validation state. */
    private bool $validationState;

    /** Current rules validation error messages. */
    private array $errors;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->validationState = true;
        $this->errors = [];
    }

    /**
     * Returns if all given fields are filled:
     *  - not nulls
     *  - not empty strings
     *
     * @param array $inputs Key of the inputs to validate
     * @param string|null $error Custom error message
     * @return Validator Current validator object
     */
    public function required(array $inputs, ?string $error = null): Validator
    {
        $defaultError = "This input is required.";

        foreach ($inputs as $input)
        {
            $inputValue = $this->getRequest()->getInput($input);

            $isFilled = $inputValue !== null && (!is_string($inputValue) || strlen(trim($inputValue)));

            if (!$isFilled)
            {
                $this->addError("required", $input, $error ?? $defaultError);
            }

            $this->validationState &= $isFilled;
        }

        return $this;
    }

    /**
     * Returns if given inputs match.
     *
     * @param string $input Key of the input to confirm
     * @param string|null $error Custom error message
     * @return Validator Current validator object
     */
    public function confirmation(string $input, ?string $error = null): Validator
    {
        $defaultError =  "Passwords does not match.";

        $inputValue = $this->getRequest()->getInput($input);
        $confirmationValue = $this->getRequest()->getInput($input . "_confirmation");

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        if ($confirmationValue === null)
        {
            $error = new UndefinedInputException($input . "confirmation");
            $error->render();
        }

        $isConfirmed = $inputValue == $confirmationValue;

        if (strlen($inputValue) && strlen($confirmationValue) && !$isConfirmed)
        {
            $this->addError("confirmation", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isConfirmed;

        return $this;
    }

    /**
     * @param string $input Key of the input to validate
     * @param int $minLength Min length to apply
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function minLength(string $input, int $minLength, ?string $error = null): Validator
    {
        $defaultError = "This field must contain at least $minLength characters.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isRespectingGivenLength = strlen($inputValue) >= $minLength;

        if (strlen($inputValue) && !$isRespectingGivenLength)
        {
            $this->addError("minLength", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isRespectingGivenLength;

        return $this;
    }

    /**
     * @param string $input Key of the input to validate
     * @param int $maxLength Max length to apply
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function maxLength(string $input, int $maxLength, ?string $error = null): Validator
    {
        $defaultError = "This field must contain at most $maxLength characters.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isRespectingGivenLength = strlen($inputValue) <= $maxLength;

        if (!$isRespectingGivenLength)
        {
            $this->addError("maxLength", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isRespectingGivenLength;

        return $this;
    }

    /**
     * @param string $input Key of the input to validate
     * @param string $pattern RegEx to apply
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function matches(string $input, string $pattern, ?string $error = null): Validator
    {
        $defaultError = "This input is not valid.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isMatchingPattern = preg_match($pattern, $inputValue);

        if (!$isMatchingPattern)
        {
            $this->addError("matches", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isMatchingPattern;

        return $this;
    }

    /**
     * @param string $input Key of the input to validate
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function email(string $input, ?string $error = null): Validator
    {
        $defaultError = "This email address is not valid.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isValidEmailAddress = filter_var($inputValue, FILTER_VALIDATE_EMAIL) == $inputValue;

        if (strlen($inputValue) && !$isValidEmailAddress)
        {
            $this->addError("email", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isValidEmailAddress;

        return $this;
    }

    /**
     * Returns if given input value is numeric.
     *
     * @param string $input Key of the input to validate
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function numeric(string $input, ?string $error = null): Validator
    {
        $defaultError = "$input must be numeric.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isNumeric = is_numeric($inputValue);

        if (!$isNumeric)
        {
            $this->addError("numeric", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isNumeric;

        return $this;
    }

    /**
     * Returns if given input value respects maximum limit.
     *
     * @param string $input Key of the input to validate
     * @param int $maximum Maximum limit
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function max(string $input, int $maximum, ?string $error = null): Validator
    {
        $defaultError = "$input cannot exceed $maximum.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isNotExceeding = (int) $inputValue <= $maximum;

        if (!$isNotExceeding)
        {
            $this->addError("max", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isNotExceeding;

        return $this;
    }

    /**
     * Returns if given input value respects minimum limit.
     *
     * @param string $input Key of the input to validate
     * @param int $minimum Minimum limit
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function min(string $input, int $minimum, ?string $error = null): Validator
    {
        $defaultError = "$input cannot be less than $minimum.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $isNotExceeding = (int) $inputValue >= $minimum;

        if (!$isNotExceeding)
        {
            $this->addError("min", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isNotExceeding;

        return $this;
    }

    /**
     * Returns if given input value is contained by given array.
     *
     * @param string $input Key of the input to validate
     * @param array $array Haystack array
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function in(string $input, array $array, ?string $error = null): Validator
    {
        $defaultError = "Given array does not include $input.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $arrayContainsInput = in_array($inputValue, $array);

        if (!$arrayContainsInput)
        {
            $this->addError("in", $input, $error ?? $defaultError);
        }

        $this->validationState &= $arrayContainsInput;

        return $this;
    }

    /**
     * Returns if given input value is between given minimum and maximum values.
     *
     * @param string $input Key of the input to validate
     * @param int $minimum Given minimum
     * @param int $maximum Given maximum
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function between(string $input,
                            int $minimum,
                            int $maximum,
                            ?string $error = null): Validator
    {
        $defaultError = "$input must be between $minimum and $maximum.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $inputValue = (int) $inputValue;

        $isBetween = $minimum <= $inputValue && $inputValue <= $maximum;

        if (!$isBetween)
        {
            $this->addError("between", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isBetween;

        return $this;
    }

    /**
     * Compares given beginning and ending dates.
     *
     * @param string $beginningDateInput Given beginning date
     * @param string $endingDateInput Given ending date
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function dateSlot(string $beginningDateInput,
                             string $endingDateInput,
                             ?string $error = null): Validator
    {
        $defaultError = "$beginningDateInput must be earlier than $endingDateInput.";

        $beginningDate = $this->getRequest()->getInput($beginningDateInput);
        $endingDate = $this->getRequest()->getInput($endingDateInput);

        if ($beginningDate === null)
        {
            $error = new UndefinedInputException($beginningDateInput);
            $error->render();
        }

        if ($endingDate === null)
        {
            $error = new UndefinedInputException($endingDateInput);
            $error->render();
        }

        $isValidDateSlot = strtotime($beginningDate) <= strtotime($endingDate);

        if (!$isValidDateSlot)
        {
            $this->addError("dateSlot", $beginningDateInput, $error ?? $defaultError);
        }

        $this->validationState &= $isValidDateSlot;

        return $this;
    }

    /**
     * Given date must be a future one.
     *
     * @param string $dateInput Key of the input to validate
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function futureDate(string $dateInput,
                               ?string $error = null): Validator
    {
        $defaultError = "The field must contain a date after the current date.";

        $date = $this->getRequest()->getInput($dateInput);

        if ($date === null)
        {
            $error = new UndefinedInputException($dateInput);
            $error->render();
        }

        $isFutureDate = strtotime($date) >= time();

        if (!$isFutureDate)
        {
            $this->addError("futureDate", $dateInput, $error ?? $defaultError);
        }

        $this->validationState &= $isFutureDate;

        return $this;
    }

    /**
     * Given file must have one of given extensions.
     *
     * @param string $fileInput Key of the file to validate
     * @param array $extensions Given accepted extensions array
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function extension(string $fileInput, array $extensions, ?string $error = null): Validator
    {
        $defaultError = "This file must have one of the following extensions: "
            . implode(', ', $extensions);

        $file = $this->getRequest()
            ->getFile($fileInput);

        if ($file === null)
        {
            $error = new UndefinedFileException($fileInput);
            $error->render();
        }

        $fileExtension = explode('/', $file->getType())[1];

        $hasAcceptedExtension = in_array($fileExtension, $extensions);

        if (!$hasAcceptedExtension)
        {
            $this->addError("extension", $fileInput, $error ?? $defaultError);
        }

        $this->validationState &= $hasAcceptedExtension;

        return $this;
    }

    /**
     * Given image must respect given max size.
     *
     * @param string $imageInput Key of the image file to validate
     * @param int $maxWidth Max width accepted
     * @param int $maxHeight Max height accepted
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function maxSize(string $imageInput, int $maxWidth, int $maxHeight, ?string $error = null): Validator
    {
        $defaultError = "This image cannot be larger than $maxWidth"
            . 'x'
            . "$maxHeight.";

        $imageFile = $this->getRequest()
            ->getFile($imageInput);

        if ($imageFile === null)
        {
            $error = new UndefinedFileException($imageInput);
            $error->render();
        }

        if ($imageFile->isImage())
        {
            $image = $imageFile->asImage();
            $imageSize = getimagesize($image->getTemporaryName());

            $hasGoodSize = $imageSize[0] <= $maxWidth && $imageSize[1] <= $maxHeight;

            if (!$hasGoodSize)
            {
                $this->addError("maxSize", $imageInput, $error ?? $defaultError);
            }

            $this->validationState &= $hasGoodSize;
        }

        return $this;
    }

    /**
     * Given input value must not exist on model table column.
     *
     * @param string $input Key of the input to validate
     * @param string $modelClass Model to use
     * @param string $column Column name
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function unique(string $input, string $modelClass, string $column, ?string $error = null): Validator
    {
        $defaultError = "This $column is already used.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $model = new $modelClass();
        $isNotUsed = !$model->isColumnValueExisting($column, $inputValue);

        if (!$isNotUsed)
        {
            $this->addError("unique", $input, $error ?? $defaultError);
        }

        $this->validationState &= $isNotUsed;

        return $this;
    }

    /**
     * Verify given password with session account one.
     *
     * @param string $input Key of the input to validate
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function password(string $input, ?string $error = null): Validator
    {
        $notLoggedDefaultError = "You must be logged to an account.";
        $wrongPasswordDefaultError = "Wrong account information.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        if (!Session::isLogged())
        {
            $this->addError("password", $input, $notLoggedDefaultError);
        }
        else
        {
            $isGoodPassword = Session::getUserAccount()->verifyPassword($inputValue);

            if (!$isGoodPassword)
            {
                $this->addError("password", $input, $error ?? $wrongPasswordDefaultError);
            }

            $this->validationState &= $isGoodPassword;
        }

        return $this;
    }

    /**
     * Verify if given input value exists in database
     * to given model table and column.
     *
     * @param string $input Key of the input to validate
     * @param string $modelClass Model to use
     * @param string $column Column name
     * @param string|null $error Optional custom error message
     * @return $this Current validator instance
     */
    public function exists(string $input, string $modelClass, string $column, ?string $error = null): Validator
    {
        $defaultError = "$input does not exist in database.";

        $inputValue = $this->getRequest()->getInput($input);

        if ($inputValue === null)
        {
            $error = new UndefinedInputException($input);
            $error->render();
        }

        $exists = $modelClass::select("COUNT(*) as count")
            ->where("name", "'$inputValue'")
            ->execute()
            ->publish()[0]
            ->count;

        if (!$exists)
        {
            $this->addError("exists", $input, $error ?? $defaultError);
        }

        $this->validationState &= $exists;

        return $this;
    }

    /**
     * @return array Error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool If there are errors
     */
    public function hasFailed(): bool
    {
        $errorsCount = 0;

        foreach ($this->errors as $rule)
        {
            $errorsCount += count($rule);
        }

        return $errorsCount;
    }

    /**
     * Add a custom error to validator object.
     *
     * @param string $rule Rule name
     * @param string $input Input name
     * @param string $message Error message
     * @return $this Current validator object
     */
    public function addError(string $rule, string $input, string $message): Validator
    {
        $this->errors[$input][$rule] = $message;
        return $this;
    }

    /**
     * @param string $input
     * @param string $rule
     * @return bool If given input does not respect given
     *              rule.
     */
    public function hasError(string $input, string $rule): bool
    {
        return isset($this->getErrors()[$input][$rule]);
    }

    /**
     * @param string $input Input key
     * @param string $rule Rule name
     * @return string|null Error message if rule failed for given input;
     *                     else NULL
     */
    public function getError(string $input, string $rule): ?string
    {
        return $this->getErrors()[$input][$rule] ?? null;
    }

    /**
     * Validator rule initialization.
     *
     * @param string $rule Rule name
     */
    private function initializeRule(string $rule): void
    {
        $this->errors[$rule] = [];
    }

    /**
     * @return Request Stored Request object
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}