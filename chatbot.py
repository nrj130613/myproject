import random, re, csv
from urllib import response

CANNED_QUESTION_FILE = 'data/canned_question.txt'
CANNED_RESPONSE_FILE = 'data/canned_response.txt'
CONVERSATION_FILE ='data/conversation.txt'
KEYWORD_FILE = 'data/keyword_response.tsv'

def main():
    print('Irasshaimase!')

    # make dictionary & list for response
    canned_q_to_r = load_canned_responses(CANNED_QUESTION_FILE, CANNED_RESPONSE_FILE, CONVERSATION_FILE)
    keyword_to_r = load_keyword_responses(KEYWORD_FILE)

    # run chatbot
    while True:
        input_text = input('\n>>> ')
        if 'bye' in input_text:
            print('Arigatou Gozaimashita!')
            break
        else:
            print(select_response(input_text, canned_q_to_r, keyword_to_r))


###### DO NOT EDIT ABOVE THIS LINE ######


# 0. functions for reading files and make dictionary/list
def load_canned_responses(question_file, response_file, conversation_file):
    """
    TODO
    1. open and read question_file and response_file, make them as response dictionary
    2. read conversation_file and make all pairs of lines in a row as reasponse dictionary
    1 and 2 must be one dictionary
    """
    questions_list = []
    response_list = []
    conver_list = []
    canned_response_dic = {}
    # normal q and a
    for line in open(CANNED_QUESTION_FILE).readlines():
        if line != '\n':
            stripped_line = line.strip()
            questions_list.append(stripped_line)
    for line in open(CANNED_RESPONSE_FILE).readlines():
        if line != '\n':
            stripped_line = line.strip()
            response_list.append(stripped_line)
            
    for i in range(len(questions_list)):
        canned_response_dic[questions_list[i]] = response_list[i]
        
    #conver
    for line in open (CONVERSATION_FILE).readlines():
        if line != '\n':
            stripped_line = line.strip()
            conver_list.append(stripped_line)
    
    for i in range(len(conver_list)):
        if i+1 < len(conver_list):
            canned_response_dic[conver_list[i]] = conver_list[i+1]
    
    return canned_response_dic

def load_keyword_responses(keyword_tsv_file):
    """
    TODO
    read keyword_tsv_file and return list of tuples [(keyword, response),...]
    """
    keyword_list = []
    keyword_response_list = []
    for line in open(KEYWORD_FILE).readlines():
        if line != '\n':
            stripped_line = line.strip()
            splitted_line = stripped_line.split('\t')
            keyword_list.append(splitted_line)
    
    for i in range(len(keyword_list)):
        keyword_tuple = tuple(keyword_list[i])
        keyword_response_list.append(keyword_tuple)
        
    
    return keyword_response_list

    


# 1. canned response
def canned_response(input_text, canned_response_dic):
    key_list = list(canned_response_dic.keys())
    for i in range(len(key_list)):
        if input_text in key_list:
            if input_text == key_list[i]:
                response = canned_response_dic[key_list[i]]
                return response
        else:
            return None


# 2. Yes/No response
def yes_no_response(input_text):
    import re
    # question or not
    pattern = r'^(Does|Do||Did|Are|Am|Is|Have|Has|Had|Will|Would|Should|Can|Could)[\w\s]+\?$'
    
    match_result = re.match(pattern, input_text)
    if match_result is not None:
        pattern = r'(Does|Do||Did|Are|Am|Is|Was|Were|Have|Has|Had|Will|Would|Should|Can|Could)\s([A-Za-z]+)\s([\w\s]+)\?$'
        find_result = re.findall(pattern, input_text)
        result_tuple = find_result[0]
        if result_tuple[0] == 'Are':
            qword = 'am'
        elif result_tuple[0] == 'Were':
            qword = 'was'
        else:
            qword = result_tuple[0]
        subject = result_tuple[1]
        restsentence = result_tuple[2]
        ask_back = result_tuple[0] + ' ' + result_tuple[1]
        answer = 'Yes, ' + 'I'  + ' ' + qword.lower() + ' ' + restsentence + '. ' + ask_back + '?'
        return answer
    else:
        return None 
    


# 3. Keyword to response
def keyword_response(input_text, keyword_response_list):
    input_text_lower = input_text[:-1].lower()
    input_text_list = input_text_lower.split(' ')
    
    for i in range(len(keyword_response_list)):
        for a in range(len(input_text_list)):
            if keyword_response_list[i][0] in input_text_list[a]:
                if keyword_response_list[i][0] == input_text_list[a]:
                    response = keyword_response_list[i][1]
                    return response
    else:
        return None


# 4. Reflecting
def reflecting(input_text):
    import re
    missing_phrase_list = []
    pattern = r"(you|You)\s([a-zA-Z',]+)\s([a-zA-Z',]+)\s([a-zA-Z',]+)?\s?(me)"
    result = re.search(pattern, input_text)
    if result is not None:
        pattern = r"[you|You]\s([a-zA-Z',]+)\s([a-zA-Z',]+)\s([a-zA-Z',]+)?\s?(me)"
        find_result = re.findall(pattern, input_text)
        for i in range(len(find_result[0])):
            if find_result[0][i] != 'me':
                if find_result[0][i] != '':
                    missing_phrase_list.append(find_result[0][i])
        for el in missing_phrase_list:
            if 'are' in missing_phrase_list:
                missing_phrase_list.pop(0)
                missing_phrase_list.insert(0, 'am')
            elif "aren't" in missing_phrase_list:
                missing_phrase_list.pop(0)
                missing_phrase_list.insert(0, 'am not')
            elif "weren't" in missing_phrase_list:
                missing_phrase_list.pop(0)
                missing_phrase_list.insert(0, "wasn't")
            elif 'were' in missing_phrase_list:
                missing_phrase_list.pop(0)
                missing_phrase_list.insert(0, 'was')
        
        missing_phrase = ' '.join(missing_phrase_list)
        
        response = 'What makes you think I ' + missing_phrase + ' you?'
        
        return response
    else:
        return None
    


# 5. Give up
def give_up():
    choices = ['Please go on.', "That's very interesting.", "I see."]
    return random.choice(choices)



# function to select response
def select_response(input_text:str, canned_response_dic:dict, keyword_response_list:list):
    """
    :param input_text: string received as user input
    :param canned_question_dic: dictionary where questions are keys and responses are values (for 1.)
    :param keyword_response_list: list of tuples: [(keyword, response),...] (for 3.)
    :return: selected_response
    TODO select response according to the priority of functions
    priority of functions:
        1. canned_response(input_text, canned_response_dic)
        2. yes_no_response(input_text)
        3. keyword_response(input_text, keyword_response_list)
        4. reflecting(input_text)
        5. give up()
    """
    canned_response(input_text, canned_response_dic)
    if canned_response(input_text, canned_response_dic) is not None:
        select_response = canned_response(input_text, canned_response_dic)
    else:
        yes_no_response(input_text)
        if yes_no_response(input_text) is not None:
            select_response = yes_no_response(input_text)
        else:
            keyword_response(input_text, keyword_response_list)
            if keyword_response(input_text, keyword_response_list) is not None:
                select_response = keyword_response(input_text, keyword_response_list)
            else:
                reflecting(input_text)
                if reflecting(input_text) is not None:
                    select_response = reflecting(input_text)
                else:
                    select_response = give_up()
                    
    return select_response
    


###### DO NOT EDIT BELOW THIS LINE ######

if __name__ == '__main__':
    main()
