using Agent;
using IniParser.Model;
using IniParser;
using Microsoft.VisualBasic.ApplicationServices;
using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Drawing;
using System.Drawing.Imaging;
using System.Runtime.CompilerServices;
using System.Windows.Forms;
//using TrayApp;
using static Agent.FRequest;
using static System.Windows.Forms.VisualStyles.VisualStyleElement;
using System.Linq.Expressions;
using System.Data.Common;
//using static TrayApp.TrayApplication;


namespace Agent
{
    public class TrayApplication : Form
    {
        public enum ITRole {boss, admin, progr, spec}
        private System.Windows.Forms.Timer IconTimer; //моргающая иконка
        private System.Windows.Forms.Timer CheckChangeTimer; //таймер проверяющий факт изменений
        public struct ChatMsg
        {
            public int chatId;
            public int userid;
            public DateTime dt;
            public string guid;
            public string msg;
            public bool isNew;
        }
        //Новая, В работе, На паузе, Ожидает подтверждения, Решена , Закрыта , Отклонена
        //public enum RequestStat { New, InProgress, OnHold, Closed, Rejected }  //PendingApproval, Resolved, 
        public class Request {
            public int id;
            public string pcname;
            public string account;
            public string employeename;
            public int urgency;
            public string message;
            //screenshot]
            public int status;// New, InProgress, OnHold, Closed, Rejected
            public string requestsolving;
            //public int idpreviosrequest;
            public ITRole itrole;
            public DateTime dt;
            public byte[] Screenshot;
            public string topic;
            public int idcategory;
            public int iduser;
            public DateTime deadline;
            public string guidemployee;
            public int chatlastmsgid;
            public SqlDataAdapter? procTbl;
            public Request() { 
                id = 0;
                pcname = string.Empty;
                account = string.Empty;
                employeename = string.Empty;
                urgency = 0;
                message = string.Empty;
                status = 1;
                requestsolving = string.Empty;
                itrole = ITRole.boss;
                dt = DateTime.MinValue;
                idcategory = 0;
                topic = string.Empty;
                iduser = 0;
                deadline = DateTime.MinValue;
                procTbl = null;
            }
            public Request(int _id, string _pcname, string _account, string _employeename, int _urgency, string _message,
                    int _status, string _requestsolving, ITRole _itrole, DateTime _dt, string _topic, int _idcategory, int _iduser, DateTime _deadline)
            {
                id = _id;
                pcname = _pcname;
                account = _account;
                employeename = _employeename;
                urgency = _urgency;
                message = _message; 
                status = _status;
                requestsolving = _requestsolving;
                itrole = _itrole;
                dt = _dt;
                topic = _topic;
                idcategory = _idcategory;
                iduser = _iduser;
                deadline = _deadline;
                chatlastmsgid = 0;
            }
        }
        public class appParameters {
            private string _pcname;
            private string _account;
            private Agent.DBClass _dB;
            public int LastMsgChat;
            public int LastProcRequest;
            public string pcname { get { return _pcname; }}
            public string account { get { return _account; } }
            public Agent.DBClass dB { get { return _dB; } }
            public appParameters(string Apcame, string Aaccount, Agent.DBClass AdB) { 
                _pcname = Apcame;
                _account = Aaccount;
                _dB = AdB;            
            }
        }
        private Agent.DBClass DBConn;
        private appParameters mainParam;
        private NotifyIcon trayIcon;
        private ContextMenuStrip trayMenu;
        private Agent.FRequest FRequestForm;
        private Agent.FList FListForm;
        private Agent.PCInfo PCAbout;
        private Request CurrentRequest;
        private bool trayIconFlag;
        public string selfAccount;
        public string selfPCName;

        public TrayApplication()
        {

            FRequestForm = new Agent.FRequest
            {
                StartPosition = FormStartPosition.CenterScreen                
            };
            FRequestForm.FormClosing += FRequest_FormClosing;

            FListForm = new Agent.FList
            {
                StartPosition = FormStartPosition.CenterScreen
            };
            FListForm.FormClosing += FListForm_FormClosing;
            


            trayMenu = new ContextMenuStrip();
            trayMenu.Items.Add("Отправить заявку", null, OnOpenNewRequest);
            trayMenu.Items.Add("Открыть реестр заявок", null, OnOpenListRequest);
            trayMenu.Items.Add("Выход", null, OnExit);

            trayIcon = new NotifyIcon
            {
                Text = "Заявки",
                //Icon = new Icon(SystemIcons.Application, 40, 40),
                //Icon = new Icon("C:\\Users\\Gigabyte\\source\\repos\\Agent\\agent.ico"),
                Icon = Properties.Resources.agent,
                ContextMenuStrip = trayMenu,
                Visible = true
            };
            string path = "";
            var parser = new FileIniDataParser();
            try
            {
                IniData data = parser.ReadFile("config.ini");
                string DS = data.GetKey("DS");
                string Catalog = data.GetKey("Catalog");
                string IntSec = data.GetKey("IntSec");
                path = $"Data Source={DS};Initial Catalog={Catalog};Integrated Security={IntSec}";

            }
            catch {
                path = "Data Source=DESKTOP-0JQ2EKE\\SQLEXPRESS;Initial Catalog=HelpDesk;Integrated Security=True";
            }
            
            
          

            trayIcon.DoubleClick += OnOpenNewRequest;
            DBConn = new Agent.DBClass();
            DBConn.DBClassConnect(path);
            Application.DoEvents();
            PCAbout = new PCInfo();
            Application.DoEvents();
            PCAbout.CollectInfo();
            Application.DoEvents();
            DBConn.SavePCInfo(PCAbout);
            Application.DoEvents();
            var LPCEl = PCAbout.LInfo.Where(p => p.TypeName == "Имя ПК");
            var LUsrEl = PCAbout.LInfo.Where(p => p.TypeName == "Пользователь");
            foreach (var p in LPCEl) { selfPCName = p.ModelName; }
            foreach (var p in LUsrEl) { selfAccount = p.ModelName; }

            mainParam = new appParameters(selfPCName, selfAccount, DBConn);
            Application.DoEvents();
            mainParam.LastMsgChat = DBConn.getIdLastChatMsg(selfAccount);
            Application.DoEvents();
            mainParam.LastProcRequest = DBConn.getIdLastRequestState(selfAccount);
            Application.DoEvents();
            trayIconFlag = false;

            IconTimer = new System.Windows.Forms.Timer();
            IconTimer.Interval = 500; // 1 секунда
            IconTimer.Tick += IconTimer_Tick;
            //IconTimer.Start();
            CheckChangeTimer = new System.Windows.Forms.Timer();
            CheckChangeTimer.Interval = 5000; // 1 секунда
            CheckChangeTimer.Tick += CheckChangeTimer_Tick;
            CheckChangeTimer.Start();
            }
        private void IconTimer_Tick(object sender, EventArgs e)
        {
            if (trayIconFlag)
            {
                trayIcon.Icon = Properties.Resources.agent;
            }
            else 
            {
                trayIcon.Icon = Properties.Resources.agentR;
            }
            trayIconFlag = !trayIconFlag;
        }

        private void CheckChangeTimer_Tick(object sender, EventArgs e)
        {
            if (DBConn.getFactOfNewChatMsg(mainParam.account, mainParam.LastMsgChat) || DBConn.getFactOfNewRequestState(mainParam.account, mainParam.LastProcRequest))
            {
                IconTimer.Start();
            }
            else {
                IconTimer.Stop();
            }
        }


        public void SaveRequest(Agent.TrayApplication.Request request)
        {
            DBConn.SaveRequest(request);
        }
        public void BOk_Click(object sender, EventArgs e)
        {
            FRequestForm.BOk.Click -= BOk_Click;//отписываемся от события
            Request FormRequest = FRequestForm.FillRequestByForm();
            DBConn.SaveRequest(FormRequest);
            FRequestForm.Hide();
        }
        public void FRequest_FormClosing(object sender, FormClosingEventArgs e)
        {
            FRequestForm = null;
            FRequestForm = new Agent.FRequest
            {
                StartPosition = FormStartPosition.CenterScreen
            };
            FRequestForm.FormClosing += FRequest_FormClosing;

        }
        public void FListForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            FListForm = null;
            FListForm = new Agent.FList
            {
                StartPosition = FormStartPosition.CenterScreen
            };
            FListForm.FormClosing += FListForm_FormClosing;

        }


        private Bitmap CaptureScreen()
        {
            // Создаем bitmap размером с экран
            Bitmap screenshot = new Bitmap(
                Screen.PrimaryScreen.Bounds.Width,
                Screen.PrimaryScreen.Bounds.Height,
                PixelFormat.Format32bppArgb
            );
            // Создаем графический объект для рисования
            using (Graphics graphics = Graphics.FromImage(screenshot))
            {
                // Копируем содержимое экрана в bitmap
                graphics.CopyFromScreen(
                    Screen.PrimaryScreen.Bounds.X,
                    Screen.PrimaryScreen.Bounds.Y,
                    0, 0,
                    Screen.PrimaryScreen.Bounds.Size,
                    CopyPixelOperation.SourceCopy
                );
            }
            return screenshot;
        }

        private byte[] ImageToByteArray(Image image)
        {
            using (MemoryStream ms = new MemoryStream())
            {
                // Сохраняем изображение в поток в формате PNG
                image.Save(ms, ImageFormat.Png);
                return ms.ToArray();
            }
        }
        public byte[] CaptureAndSaveScreenshot()
        {
            try
            {
                // Создаем скриншот
                Bitmap screenshot = CaptureScreen();

                // Конвертируем в массив байтов
                byte[] imageData = ImageToByteArray(screenshot);
                return imageData;
                // Сохраняем в базу данных
                //SaveToDatabase(imageData);

                //MessageBox.Show("Скриншот успешно сохранен в базу данных!");
            }
            catch (Exception ex)
            {
                return null;
                //MessageBox.Show($"Ошибка: {ex.Message}");
            }
        }
        private void OnOpenListRequest(object sender, EventArgs e)
        {
            FListForm.Show();
            //FListForm.BOk.Click += BOk_Click;
            FListForm.WindowState = FormWindowState.Normal;
            FListForm.Activate();
            FListForm.FillFormControl(mainParam);
        }
        private void OnOpenNewRequest(object sender, EventArgs e)
        {
            
            byte[] imageData = CaptureAndSaveScreenshot();
            FRequestForm.Show();
            FRequestForm.BOk.Click += BOk_Click;
            FRequestForm.WindowState = FormWindowState.Normal;
            FRequestForm.Activate();
            CurrentRequest = new Request();
            if (DBConn.Connected) {
                FRequestForm.Text = "Заявка";                
                CurrentRequest.Screenshot = imageData;
                CurrentRequest.account = selfAccount;
                CurrentRequest.pcname = selfPCName;
                CurrentRequest.deadline = DateTime.Now.AddDays(3);
            }
            else {
                FRequestForm.Text = "Заявка    (ОШИБКА подключения к БД)";
            }
            FRequestForm.FillFormControl(mainParam);
            FRequestForm.FillFormByRequest(CurrentRequest);
        }

        private void OnExit(object sender, EventArgs e)
        {
            IconTimer.Stop();
            IconTimer.Dispose();
            CheckChangeTimer.Stop();
            CheckChangeTimer.Dispose();



            trayIcon.Visible = false;
            Application.Exit();
        }

        protected override void OnLoad(EventArgs e)
        {
            Visible = false; // Скрываем основную форму
            ShowInTaskbar = false; // Не показываем в панели задач
            base.OnLoad(e);
        }

        [STAThread]
        public static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new TrayApplication());
        }
    }
}


