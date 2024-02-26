<?php

namespace MvcliteCore\Engine;

use Exception;
use MvcliteCore\Engine\InternalResources\Storage;

class MvcLiteException extends Exception
{
    /** MVCLite exception dialog title. */
    private string $title;

    /**
     * @param string $title New MVCLite exception dialog title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string MVCLite exception title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function __construct()
    {
        parent::__construct();

        $this->code = "MVCLITE_UNKNOWN_ERROR";
        $this->message = "An unknwon error is thrown by MVCLite.";

        $this->title = "MVCLite Fatal Error";
    }

    /**
     * @return string HTML error dialog window
     */
    public function getDialog(): string
    {
        $code = $this->getCode();
        $message = $this->getMessage();
        $file = $this->getFile();
        $title = $this->getTitle();

        self::importCss();

        $html = "<div mvclite-dialog>
                     <div class='dialog-window'>
                         <h1>
                             $title
                         </h1>
    
                         <hr />
                         
                         <p>
                             <span class='bold'>Code:</span>
                             <span class='mono'>$code</span>
                         </p>
                         
                         <p>
                             <span class='bold'>File:</span>
                             <span class='mono'>$file</span>
                         </p>
                         
                         " . ($message ? "<p class='mono message'>$message</p>" : "") . "
                     </div>
                 </div>";

        return $html;
    }

    /**
     * HTML error dialog window rendering.
     */
    public function render(): void
    {
        echo $this->getDialog();
        die;
    }

    /**
     * Import debug rendering CSS.
     */
    private static function importCss(): void
    {
        $debugCss = file_get_contents("./InternalResources/ExceptionRendering/rendering.css");

        echo "<style>$debugCss</style>";
    }
}