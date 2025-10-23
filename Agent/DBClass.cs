using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.ListView;
using static System.Windows.Forms.VisualStyles.VisualStyleElement;
using System.Xml.Linq;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.StartPanel;
using static Agent.TrayApplication;
using System.Security.Principal;
using System.Windows.Forms;
using Microsoft.VisualBasic.ApplicationServices;
using System.Net.NetworkInformation;
using System.Security.Cryptography;
using System.DirectoryServices.Protocols;
using System.Runtime.Intrinsics.Arm;


namespace Agent
{

    public class DBClass
    {
        public SqlConnection conn = null;
        public bool Connected = false;
        public void DBClassConnect(string path) {

            conn = new SqlConnection();
            conn.ConnectionString = path;
            try
            {
                conn.Open();
                Connected = true;
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
            /*
            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select * from users", conn);
            rdr = cmd.ExecuteReader();
            */
            // return true;
        }

        public DataTable GetSprInfo(string ATableName, bool IncludeAll)
        {
            DataTable dt = new DataTable();
            dt.Columns.Add("Id", typeof(int));
            dt.Columns.Add("Name", typeof(string));

            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select id, name  FROM "+ ATableName, conn);
            
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                if (IncludeAll)
                {
                    dt.Rows.Add(0, "Все");
                }
                while (rdr.Read()) {
                    dt.Rows.Add(rdr.GetInt32(rdr.GetOrdinal("id")), rdr.GetString(rdr.GetOrdinal("name")));
                }
                rdr.Close();
            }
            return dt;
        }
        public DataTable GetUrgency(bool IncludeAll)
        {
            DataTable dtUrgency = new DataTable();
            dtUrgency.Columns.Add("Id", typeof(int));
            dtUrgency.Columns.Add("Name", typeof(string));
            if (IncludeAll) { dtUrgency.Rows.Add(0, "Все"); };

            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select id, name  FROM urgencies ", conn);

            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                while (rdr.Read())
                {
                    dtUrgency.Rows.Add(rdr.GetInt32(rdr.GetOrdinal("id")), rdr.GetString(rdr.GetOrdinal("name")));
                }
                rdr.Close();
            }

            /*dtUrgency.Rows.Add(1, "Пожелание");
            dtUrgency.Rows.Add(2, "Мало важная");
            dtUrgency.Rows.Add(3, "Важная");
            dtUrgency.Rows.Add(4, "Очень важная");*/
            return dtUrgency;
        }
        public DataTable GetStatus(bool IncludeAll)
        {
            DataTable dtStatus = new DataTable();
            dtStatus.Columns.Add("Id", typeof(int));
            dtStatus.Columns.Add("Name", typeof(string));

            if (IncludeAll)
            {
                dtStatus.Rows.Add(0, "Все");
            }
            dtStatus.Rows.Add(1, "Новая");
            dtStatus.Rows.Add(2, "В работе");
            dtStatus.Rows.Add(3, "На паузе");
            dtStatus.Rows.Add(4, "На проверке");
            dtStatus.Rows.Add(5, "Закрыта");
            dtStatus.Rows.Add(100, "Актуальные");

            return dtStatus;

        }
        public List<ChatMsg> GetMessagesFromBase(int requestId, int AfterId)
        {

            List<ChatMsg> LChatMsg = new List<ChatMsg>();
            SqlDataReader rdr = null;
            //ChatMsg msg; 
            SqlCommand cmd = new SqlCommand("select chat.id as chatid, guid, dt, msg, isNew from chat left join users on (users.employeeguid=guid) where idrequest=@idrequest and chat.id>@fromId order by chat.id", conn);//users.id as userid, 
            cmd.Parameters.AddWithValue("@idrequest", requestId);
            cmd.Parameters.AddWithValue("@fromId", AfterId);
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                while (rdr.Read())
                {/*
                    msg = new ChatMsg();
                    msg.chatId = rdr.GetInt32(rdr.GetOrdinal("chatid"));
                    msg.guid = rdr.GetString(rdr.GetOrdinal("guid"));
                    msg.dt = rdr.GetDateTime(rdr.GetOrdinal("dt"));
                    msg.isNew = rdr.GetInt32(rdr.GetOrdinal("isNew"))==1? true: false;
                    LChatMsg.Add(msg);*/
                    
                    LChatMsg.Add(new ChatMsg { 
                        chatId = rdr.GetInt32(rdr.GetOrdinal("chatid")), 
                        guid= rdr.GetString(rdr.GetOrdinal("guid")),
                        msg = rdr.GetString(rdr.GetOrdinal("msg")),
                        //userid= rdr.GetInt32(rdr.GetOrdinal("userid")),
                        dt = rdr.GetDateTime(rdr.GetOrdinal("dt")),
                        isNew = rdr.GetInt32(rdr.GetOrdinal("isNew")) == 1 ? true : false
                    });
                    
                }
                rdr.Close();
            }
            rdr.Close();
            return LChatMsg;

        }
        public void SendMessageToBase(int requestId, string account, string AMsg)
        {

            string insertQuery =
                "insert INTO chat (idrequest, guid, dt, msg, isNew) values (@idrequest, (select top 1 guid from employees where account=@account), CURRENT_TIMESTAMP, @message, 1)";
            using (SqlCommand cmdIns = new SqlCommand(insertQuery, conn))
            {
                cmdIns.Parameters.AddWithValue("@idrequest", requestId);
                cmdIns.Parameters.AddWithValue("@account", account);
                cmdIns.Parameters.AddWithValue("@message", AMsg);
                cmdIns.ExecuteNonQuery();
            }


        }
        public void SavePCInfo(PCInfo PCAbout) {
            var LPCEl = PCAbout.LInfo.Where(p => p.TypeName == "Имя ПК");
            var LUsrEl = PCAbout.LInfo.Where(p => p.TypeName == "Пользователь");
            var LDsplEl = PCAbout.LInfo.Where(p => p.TypeName == "Полное имя");
            
            string PCName = ""; int PCId = 0;
            string UserName=""; int UserId = 0;
            string DisplayName = "";
            foreach (var p in LPCEl)            
                PCName = p.ModelName;
            foreach (var p in LUsrEl)  
                UserName = p.ModelName;
            foreach (var p in LDsplEl)
                DisplayName = p.ModelName;

            if (PCName != "") { 
                PCId = GetPCNameId(PCName);
                SavePCComposition(PCId, PCAbout.LInfo);
            }
            if (UserName != "") UserId = GetDomenNameId(UserName, DisplayName);
        }
        public int GetPCNameId(string PCName) {
            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select count(*) cnt, max(id) id from PC where pcname=@PCName", conn);
            cmd.Parameters.Add("@PCName", SqlDbType.NVarChar, 50).Value = PCName;
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                rdr.Read();
                if (rdr.GetInt32(rdr.GetOrdinal("cnt")) == 0)
                {
                    rdr.Close();
                    string insertQuery = "INSERT INTO PC (PCName) VALUES (@PCName)";
                    using (SqlCommand cmdIns = new SqlCommand(insertQuery, conn))
                    {
                        cmdIns.Parameters.AddWithValue("@PCName", PCName);
                        cmdIns.ExecuteNonQuery();
                    }
                }else rdr.Close();
            }
            cmd = new SqlCommand("select count(*) cnt, max(id) id from PC where pcname=@PCName", conn);
            cmd.Parameters.Add("@PCName", SqlDbType.NVarChar, 50).Value = PCName;
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                rdr.Read();                
                if (rdr.GetInt32(rdr.GetOrdinal("cnt")) != 0)
                {
                    int res = rdr.GetInt32(rdr.GetOrdinal("id"));
                    rdr.Close();
                    return res;
                }
            }
            rdr.Close();
            return 0;
        }
        public int GetDomenNameId(string DomenName, string OfficialUserName)
        {
            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select count(*) cnt, max(id) id from [dbo].[domen_names] where account=@account", conn);
            cmd.Parameters.Add("@account", SqlDbType.NVarChar, 50).Value = DomenName;
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                rdr.Read();
                if (rdr.GetInt32(rdr.GetOrdinal("cnt")) == 0)
                {
                    rdr.Close();
                    string insertQuery = $@" INSERT INTO [dbo].[domen_names] (account, guidemployee) VALUES (@account, (select top 1 guid from employees where employees.account = @account) )";
                    //INSERT INTO[dbo].[domen_names] (account, guidemployee) VALUES('Dolgih@mycompany.ru', (select top 1 guid from employees where employees.account = 'Dolgih@mycompany.ru'))
                    using (SqlCommand command = new SqlCommand(insertQuery, conn))
                    {
                        // Добавляем параметры для защиты от SQL-инъекций
                        command.Parameters.AddWithValue("@account", DomenName);
                        command.Parameters.AddWithValue("@employeename", OfficialUserName);
                        command.ExecuteNonQuery();
                    }
                }else rdr.Close();
            }
            cmd = new SqlCommand("select count(*) cnt, max(id) id from [dbo].[domen_names] where account=@account", conn);
            cmd.Parameters.Add("@account", SqlDbType.NVarChar, 50).Value = DomenName;
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                rdr.Read();
                if (rdr.GetInt32(rdr.GetOrdinal("cnt")) != 0)
                {
                    int res = rdr.GetInt32(rdr.GetOrdinal("id"));
                    rdr.Close();
                    return res;
                }
            }
            return 0;
        }
        public void SaveRequest(Agent.TrayApplication.Request request) {
            int idrequest=0;
            if (request == null) { return; }
            if (request.id == 0)
            {
                string insertQuery =
                    "INSERT INTO request ([pcname],[account],[employeename],[urgency],[message],[dt],[screenshot], topic, idcategory, deadline, itrole) OUTPUT INSERTED.ID " +
                        "values (@pcname,@account,@employeename,@urgency,@message,CURRENT_TIMESTAMP, @ImageData, @topic, @idcategory, @deadline, @itrole)";
                using (SqlCommand cmdIns = new SqlCommand(insertQuery, conn))
                {
                    cmdIns.Parameters.AddWithValue("@pcname", request.pcname);
                    cmdIns.Parameters.AddWithValue("@account", request.account);
                    cmdIns.Parameters.AddWithValue("@employeename", request.employeename);
                    cmdIns.Parameters.AddWithValue("@urgency", request.urgency);
                    cmdIns.Parameters.AddWithValue("@message", request.message);
                    cmdIns.Parameters.AddWithValue("@ImageData", request.Screenshot);
                    cmdIns.Parameters.AddWithValue("@topic", request.topic);
                    cmdIns.Parameters.AddWithValue("@idcategory", request.idcategory);
                    cmdIns.Parameters.AddWithValue("@deadline", request.deadline);
                    switch (request.itrole)
                    {
                        case ITRole.boss: cmdIns.Parameters.AddWithValue("@itrole", "boss"); break;
                        case ITRole.admin: cmdIns.Parameters.AddWithValue("@itrole", "admin"); break;
                        case ITRole.progr: cmdIns.Parameters.AddWithValue("@itrole", "progr"); break;
                        case ITRole.spec: cmdIns.Parameters.AddWithValue("@itrole", "spec"); break;
                    }
                     request.id = (int)cmdIns.ExecuteScalar();                    
                }
                if (request.id != 0) {
                    string insertProcQuery = "";
                    insertProcQuery = "INSERT INTO proc_request ([idrequest], [dt], [status], [itrole], iduser) values (@idrequest, CURRENT_TIMESTAMP, @status, @itrole, @iduser)"; //, [iduser], iduser
                        
                    using (SqlCommand cmdIns = new SqlCommand(insertProcQuery, conn))
                    {
                        cmdIns.Parameters.AddWithValue("@idrequest", request.id);
                        switch (request.itrole)
                        {
                            case ITRole.boss: cmdIns.Parameters.AddWithValue("@itrole", "boss"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break; 
                            case ITRole.admin: cmdIns.Parameters.AddWithValue("@itrole", "admin"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break;
                            case ITRole.progr: cmdIns.Parameters.AddWithValue("@itrole", "progr"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break;
                            case ITRole.spec:
                                {
                                    cmdIns.Parameters.AddWithValue("@itrole", "spec");
                                    cmdIns.Parameters.AddWithValue("@iduser", request.iduser);
                                    break;
                                };
                        }
                        cmdIns.Parameters.AddWithValue("@status", request.status);
                        idrequest = (int)cmdIns.ExecuteNonQuery();
                    } 
                }
            }
            else {
                string updateQuery = "update request set pcname = @pcname, account=@account, employeename=@employeename, urgency=@urgency, message=@message, topic=@topic, idcategory=@idcategory, deadline=@deadline " +
                    "where id=@id";
                using (SqlCommand cmdUpd = new SqlCommand(updateQuery, conn))
                {
                    cmdUpd.Parameters.AddWithValue("@pcname", request.pcname);
                    cmdUpd.Parameters.AddWithValue("@account", request.account);
                    cmdUpd.Parameters.AddWithValue("@employeename", request.employeename);
                    cmdUpd.Parameters.AddWithValue("@urgency", request.urgency);
                    cmdUpd.Parameters.AddWithValue("@message", request.message);
                    cmdUpd.Parameters.AddWithValue("@id", request.id);
                    cmdUpd.Parameters.AddWithValue("@topic", request.topic);
                    cmdUpd.Parameters.AddWithValue("@idcategory", request.idcategory);
                    cmdUpd.Parameters.AddWithValue("@deadline", request.deadline);
                    cmdUpd.ExecuteNonQuery();
                }
                //TODO доделать сохранение изменений в заявке
                string insertProcQuery = "INSERT INTO proc_request ([idrequest], [dt], [status], [itrole], [iduser]) " + //
                    "values (@idrequest, CURRENT_TIMESTAMP, @status, @itrole, @iduser)"; //, 
                using (SqlCommand cmdIns = new SqlCommand(insertProcQuery, conn))
                {
                    cmdIns.Parameters.AddWithValue("@idrequest", request.id);
                    switch (request.itrole)
                    {
                        case ITRole.boss: cmdIns.Parameters.AddWithValue("@itrole", "boss"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break;
                        case ITRole.admin: cmdIns.Parameters.AddWithValue("@itrole", "admin"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break;
                        case ITRole.progr: cmdIns.Parameters.AddWithValue("@itrole", "progr"); cmdIns.Parameters.AddWithValue("@iduser", DBNull.Value); break;
                        case ITRole.spec:
                            { 
                                cmdIns.Parameters.AddWithValue("@itrole", "spec");
                                cmdIns.Parameters.AddWithValue("@iduser", request.iduser);
                                break; 
                            }
                    }
                    
                    cmdIns.Parameters.AddWithValue("@status", request.status);
                    //cmdIns.Parameters.AddWithValue("@iduser", request.);
                    idrequest = (int)cmdIns.ExecuteNonQuery();
                }

            }

        }
        public Request GetRequest(int requestid)
        {
            Agent.TrayApplication.Request request = new Agent.TrayApplication.Request();

            SqlDataReader rdr = null;
            SqlCommand cmd = new SqlCommand("select *  FROM full_request_info where id=@id", conn);
            cmd.Parameters.AddWithValue("@id", requestid);
            rdr = cmd.ExecuteReader();
            if (rdr.HasRows)
            {
                while (rdr.Read())
                {                    
                    request.id = rdr.GetInt32(rdr.GetOrdinal("id"));
                    request.pcname = rdr.GetString(rdr.GetOrdinal("pcname"));
                    request.account = rdr.GetString(rdr.GetOrdinal("account")); 
                    request.employeename = rdr.GetString(rdr.GetOrdinal("employeename"));
                    request.urgency = rdr.GetInt32(rdr.GetOrdinal("urgency"));
                    request.message = rdr.GetString(rdr.GetOrdinal("message"));
                    request.status = rdr.GetInt32(rdr.GetOrdinal("status_proc"));
                    request.guidemployee = rdr.GetString(rdr.GetOrdinal("guidemployee")); //разобраться


                    if (!rdr.IsDBNull("itrole"))
                    {
                        //request.requestsolving = _requestsolving;
                        switch (rdr.GetString(rdr.GetOrdinal("itrole_proc")))
                        {
                            case "boss":
                                request.itrole = ITRole.boss;
                                break;
                            case "admin":
                                request.itrole = ITRole.admin;
                                break;
                            case "progr":
                                request.itrole = ITRole.progr;
                                break;
                            default:
                                request.itrole = ITRole.spec;
                                break;
                        }
                    }
                    //request.itrole = (ITRole)rdr.GetString(rdr.GetOrdinal("itrole")); 
                    request.dt = rdr.GetDateTime(rdr.GetOrdinal("dt"));
                    request.topic = rdr.GetString(rdr.GetOrdinal("topic"));
                    request.idcategory = rdr.GetInt32(rdr.GetOrdinal("idcategory"));
                    if (!rdr.IsDBNull("iduser_proc"))
                    {
                        request.iduser = rdr.GetInt32(rdr.GetOrdinal("iduser_proc"));
                    }
                    if (!rdr.IsDBNull("description_proc"))
                    {
                        request.requestsolving = rdr.GetString(rdr.GetOrdinal("description_proc"));
                    }
                    request.deadline = rdr.GetDateTime(rdr.GetOrdinal("deadline"));
                    request.procTbl = getProcRequests(request.id);
                    //dt.Rows.Add(rdr.GetInt32(rdr.GetOrdinal("id")), rdr.GetString(rdr.GetOrdinal("name")));
                }
                rdr.Close();
            }
            return request;
        }
        public void SavePCComposition(int PCId, List<PCInfo.ElementInfo> LInfo) {
            int num = 1;
            var LPCEl = LInfo.Where(p => p.isSystemInfo == true);
            string insertQuery = "INSERT INTO PC_composition ([idpc],[typename],[modelname],[cnt],[fulldescription],[dt],[ord]) VALUES (@idpc, @typename,@modelname,@cnt,@fulldescription,CURRENT_TIMESTAMP,@ord)";
            foreach (var p in LPCEl)
            {                
                using (SqlCommand cmdIns = new SqlCommand(insertQuery, conn))
                {
                    cmdIns.Parameters.AddWithValue("@idpc", PCId);
                    cmdIns.Parameters.AddWithValue("@typename", p.TypeName);
                    cmdIns.Parameters.AddWithValue("@modelname", p.ModelName);
                    cmdIns.Parameters.AddWithValue("@cnt", p.cnt);
                    //cmdIns.Parameters.AddWithValue("@shortdescription", p.);
                    cmdIns.Parameters.AddWithValue("@fulldescription", p.FullDescription);
                    cmdIns.Parameters.AddWithValue("@ord", num);
                    num++;
                    cmdIns.ExecuteNonQuery();                    
                }
            }

        }

        public SqlDataAdapter getProcRequests(int AResquestId)
        {

            //select id, status_proc_name, urgency_name, idcategory_name, topic, employeename, name_users, pcname, dt, deadline, account, urgency, message,
            //finalstatus, iduser_proc, itrole_proc, itrole_proc_name, idcategory, dt_proc, description_proc, status_proc, role_users, employeeguid_users,
            //blocking_users, guidemployee FROM full_request_info ";

            string query = "SELECT dt, status_name, description,user_name FROM request_proc_info where idrequest=@idrequest order by dt desc";


            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@idrequest", AResquestId);

            SqlDataAdapter adapter = new SqlDataAdapter(selCmd);
            return adapter;
        }
        public SqlDataAdapter getAllRequests(string account, int AStatus, int APriority, int ACategory, string ASearch, bool ADeadline)
        {

            //select id, status_proc_name, urgency_name, idcategory_name, topic, employeename, name_users, pcname, dt, deadline, account, urgency, message,
            //finalstatus, iduser_proc, itrole_proc, itrole_proc_name, idcategory, dt_proc, description_proc, status_proc, role_users, employeeguid_users,
            //blocking_users, guidemployee FROM full_request_info ";

            string query = "SELECT id,urgency,idcategory, status_proc as status, topic, itrole_proc_name, employeename, name_users, pcname, dt, deadline FROM full_request_info where account=@account";
            if (ASearch != null && ASearch != "")
            {
                query += " and (id like @str or status_proc_name like @str or urgency_name like @str or topic like @str or employeename like @str or name_users like @str or  pcname like @str)";
            }
            if (AStatus > 0)
            {
                if (AStatus == 100)
                {
                    query += " and (status_proc <> 5)";
                }
                else
                {
                    query += $" and (status_proc = {AStatus})";
                }
            }
            if (APriority > 0) { query += $" and (urgency={APriority})"; }
            if (ACategory > 0) { query += $" and (idcategory={ACategory})"; }
            if (ADeadline == true) { query += $" and (deadline<@deadline)"; }


            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@account", account);
            if (ASearch != null && ASearch != "")
            {
                selCmd.Parameters.AddWithValue("@str", "%" + ASearch + "%");
            }
            if (ADeadline == true)
            {
                selCmd.Parameters.AddWithValue("@deadline", DateTime.Now);
            }
            SqlDataAdapter adapter = new SqlDataAdapter(selCmd);
            return adapter;
        }

        public void DBClassClose() { 
            conn.Close();
        }
        public bool getFactOfNewChatMsg(string account, int lastChatMsg) {
            string query = "select count(chat.id) cnt from chat where idrequest in (select id from request where account=@account) and chat.id > @chat_id and isnew=1";

            SqlDataReader rdr = null;
            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@account", account);
            selCmd.Parameters.AddWithValue("@chat_id", lastChatMsg);
            rdr = selCmd.ExecuteReader();

            if (rdr.HasRows)
            {
                while (rdr.Read())
                {
                    if (rdr.GetInt32(rdr.GetOrdinal("cnt")) > 0) { rdr.Close();  return true; }
                }
            }
            rdr.Close();

            return false;
        }
        public bool getFactOfNewRequestState(string account, int lastRequestState)
        {
            string query = "select count(proc_request.id) cnt from proc_request where idrequest in (select id from request where account=@account) and proc_request.id > @request_id and isnew=1";

            SqlDataReader rdr = null;
            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@account", account);
            selCmd.Parameters.AddWithValue("@request_id", lastRequestState);
            rdr = selCmd.ExecuteReader();

            if (rdr.HasRows)
            {
                while (rdr.Read())
                {
                    if (rdr.GetInt32(rdr.GetOrdinal("cnt")) > 0) { rdr.Close();  return true; }
                }
            }
            rdr.Close();

            return false;
        }
        public int getIdLastChatMsg(string account) {
            string query = "select max(chat.id) maxId from chat where idrequest in (select id from request where account=@account) and isnew=1";

            SqlDataReader rdr = null;
            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@account", account);            
            rdr = selCmd.ExecuteReader();

            if (rdr.HasRows)
            {
                while (rdr.Read())
                {
                    if (rdr.GetInt32(rdr.GetOrdinal("maxId")) > 0) 
                    { 
                        var res = rdr.GetInt32(rdr.GetOrdinal("maxId"));
                        rdr.Close();
                        return res;
                    }
                }
            }
            rdr.Close();
            return 0;
        }
        public int getIdLastRequestState(string account)
        {
            string query = "select max(proc_request.id) maxId from proc_request where idrequest in (select id from request where account=@account) and isnew=1";

            SqlDataReader rdr = null;
            SqlCommand selCmd = new SqlCommand(query, conn);
            selCmd.Parameters.AddWithValue("@account", account);
            rdr = selCmd.ExecuteReader();

            if (rdr.HasRows)
            {
                while (rdr.Read())
                {
                    if (rdr.GetInt32(rdr.GetOrdinal("maxId")) > 0)
                    {
                        var res = rdr.GetInt32(rdr.GetOrdinal("maxId"));
                        rdr.Close();
                        return res;

                    }
                }
            }
            rdr.Close();
            return 0;
        }




    }
}
